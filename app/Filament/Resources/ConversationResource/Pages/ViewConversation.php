<?php

namespace App\Filament\Resources\ConversationResource\Pages;

use App\Filament\Resources\ConversationResource;
use App\Models\Message;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\MaxWidth;

class ViewConversation extends ViewRecord
{
    protected static string $resource = ConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('join_conversation')
                ->label('Join as Human')
                ->icon('heroicon-m-user-plus')
                ->color('success')
                ->visible(fn () => !$this->record->human_participants || !in_array(auth()->id(), $this->record->human_participants ?? []))
                ->action(function () {
                    $this->record->addHumanParticipant(auth()->user());
                    $this->redirect(static::getUrl(['record' => $this->record]));
                }),
                
            Actions\Action::make('pause_conversation')
                ->label('Pause Conversation')
                ->icon('heroicon-m-pause')
                ->color('warning')
                ->visible(fn () => $this->record->status === 'active')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->pauseForHuman(auth()->user());
                    
                    // Add a system message about the pause
                    Message::create([
                        'conversation_id' => $this->record->id,
                        'user_id' => auth()->id(),
                        'sender_type' => 'human_user',
                        'content' => '🔄 ' . auth()->user()->name . ' paused the conversation for human input.',
                        'type' => 'human_pause',
                        'metadata' => ['system_message' => true],
                    ]);
                    
                    $this->redirect(static::getUrl(['record' => $this->record]));
                }),
                
            Actions\Action::make('resume_conversation')
                ->label('Resume Conversation')
                ->icon('heroicon-m-play')
                ->color('success')
                ->visible(fn () => $this->record->status === 'paused')
                ->requiresConfirmation()
                ->action(function () {
                    // Add a system message about resuming
                    Message::create([
                        'conversation_id' => $this->record->id,
                        'user_id' => auth()->id(),
                        'sender_type' => 'human_user',
                        'content' => '▶️ ' . auth()->user()->name . ' resumed the conversation.',
                        'type' => 'human_resume',
                        'metadata' => ['system_message' => true],
                    ]);
                    
                    $this->record->resume();
                    $this->redirect(static::getUrl(['record' => $this->record]));
                }),
                
            Actions\Action::make('add_human_message')
                ->label('Add Message')
                ->icon('heroicon-m-chat-bubble-left-right')
                ->color('primary')
                ->visible(fn () => in_array(auth()->id(), $this->record->human_participants ?? []))
                ->modalHeading('Add Human Message to Conversation')
                ->modalWidth(MaxWidth::ExtraLarge)
                ->form([
                    Forms\Components\Textarea::make('content')
                        ->label('Your Message')
                        ->required()
                        ->rows(6)
                        ->placeholder('Write your message to the AI authors...'),
                    Forms\Components\Select::make('type')
                        ->label('Message Type')
                        ->options([
                            'human_input' => 'General Input',
                            'story_contribution' => 'Story Contribution',
                            'revision_suggestion' => 'Revision Suggestion',
                        ])
                        ->default('human_input')
                        ->required(),
                ])
                ->action(function (array $data) {
                    Message::create([
                        'conversation_id' => $this->record->id,
                        'user_id' => auth()->id(),
                        'sender_type' => 'human_user',
                        'content' => $data['content'],
                        'type' => $data['type'],
                        'metadata' => [
                            'human_author' => auth()->user()->name,
                            'timestamp' => now()->toISOString(),
                        ],
                    ]);
                    
                    $this->redirect(static::getUrl(['record' => $this->record]));
                }),
                
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getInfolist(): Infolists\Infolist
    {
        return Infolists\Infolist::make()
            ->schema([
                Infolists\Components\Section::make('Conversation Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('title'),
                        Infolists\Components\TextEntry::make('topic'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'paused' => 'warning',
                                'completed' => 'info',
                                'archived' => 'gray',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('allows_human_interaction')
                            ->label('Human Interaction')
                            ->getStateUsing(fn ($record) => $record->allows_human_interaction ? 'Enabled' : 'Disabled')
                            ->badge()
                            ->color(fn ($record) => $record->allows_human_interaction ? 'success' : 'gray'),
                    ])->columns(2),
                    
                Infolists\Components\Section::make('Participants')
                    ->schema([
                        Infolists\Components\TextEntry::make('ai_authors')
                            ->label('AI Authors')
                            ->getStateUsing(function ($record) {
                                return $record->getParticipantAuthors()
                                    ->pluck('name')
                                    ->map(fn ($name) => "🤖 {$name}")
                                    ->join(', ');
                            }),
                        Infolists\Components\TextEntry::make('human_participants')
                            ->label('Human Participants')
                            ->getStateUsing(function ($record) {
                                return $record->getHumanParticipants()
                                    ->pluck('name')
                                    ->map(fn ($name) => "👤 {$name}")
                                    ->join(', ') ?: 'None';
                            }),
                    ])->columns(1),
                    
                Infolists\Components\Section::make('Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('message_count'),
                        Infolists\Components\TextEntry::make('last_message_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('paused_at')
                            ->dateTime()
                            ->visible(fn ($record) => $record->paused_at),
                        Infolists\Components\TextEntry::make('pausedByUser.name')
                            ->label('Paused By')
                            ->visible(fn ($record) => $record->paused_by_user_id),
                    ])->columns(2),
                    
                Infolists\Components\Section::make('Recent Messages')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('messages')
                            ->label('')
                            ->getStateUsing(function ($record) {
                                return $record->messages()
                                    ->with(['author', 'user'])
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get()
                                    ->map(function ($message) {
                                        $sender = $message->isHumanMessage() 
                                            ? '👤 ' . ($message->user->name ?? 'Unknown User')
                                            : '🤖 ' . ($message->author->name ?? 'Unknown AI');
                                            
                                        return [
                                            'sender' => $sender,
                                            'type' => $message->type,
                                            'content' => strlen($message->content) > 200 
                                                ? substr($message->content, 0, 200) . '...'
                                                : $message->content,
                                            'created_at' => $message->created_at->format('M j, Y H:i'),
                                        ];
                                    });
                            })
                            ->schema([
                                Infolists\Components\TextEntry::make('sender')
                                    ->label('From'),
                                Infolists\Components\TextEntry::make('type')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'human_pause' => 'warning',
                                        'human_resume' => 'success', 
                                        'human_input' => 'primary',
                                        'story_contribution' => 'info',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('content')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Time'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}