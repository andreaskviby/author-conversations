<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Filament\Resources\MessageResource\RelationManagers;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('conversation_id')
                    ->relationship('conversation', 'title')
                    ->required(),
                Forms\Components\Select::make('author_id')
                    ->relationship('author', 'name')
                    ->visible(fn (Forms\Get $get) => $get('sender_type') === 'ai_author' || !$get('sender_type')),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->visible(fn (Forms\Get $get) => $get('sender_type') === 'human_user'),
                Forms\Components\Select::make('sender_type')
                    ->options([
                        'ai_author' => 'AI Author',
                        'human_user' => 'Human User',
                    ])
                    ->required()
                    ->default('ai_author')
                    ->live(),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('type')
                    ->options([
                        'message' => 'Message',
                        'story_contribution' => 'Story Contribution',
                        'chapter_draft' => 'Chapter Draft',
                        'revision_suggestion' => 'Revision Suggestion',
                        'human_input' => 'Human Input',
                        'human_pause' => 'Human Pause',
                        'human_resume' => 'Human Resume',
                    ])
                    ->required()
                    ->default('message'),
                Forms\Components\Textarea::make('metadata')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('context_summary')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('word_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_processed')
                    ->required(),
                Forms\Components\Toggle::make('is_flagged')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('conversation.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sender')
                    ->label('Sender')
                    ->getStateUsing(function ($record) {
                        if ($record->sender_type === 'human_user') {
                            return '👤 ' . ($record->user->name ?? 'Unknown User');
                        } else {
                            return '🤖 ' . ($record->author->name ?? 'Unknown AI');
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'human_pause' => 'warning',
                        'human_resume' => 'success',
                        'human_input' => 'primary',
                        'story_contribution' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('word_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_processed')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_flagged')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
}
