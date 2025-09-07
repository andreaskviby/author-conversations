<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo user
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
        ]);

        // Create AI authors
        $author1 = Author::create([
            'user_id' => $user->id,
            'name' => 'Aria Nightingale',
            'bio' => 'A mysterious and creative writer who specializes in weaving intricate plots with psychological depth.',
            'personality_traits' => ['creative', 'mysterious', 'analytical', 'passionate'],
            'writing_style' => [
                'tone' => 'dark_atmospheric',
                'complexity' => 'high',
                'perspective' => 'third_person',
                'genre_preferences' => ['mystery', 'thriller', 'psychological']
            ],
            'expertise_areas' => ['psychology', 'criminology', 'urban_legends'],
            'interests' => ['noir_films', 'classical_music', 'abandoned_places'],
            'preferred_language' => 'en',
            'is_active' => true,
        ]);

        $author2 = Author::create([
            'user_id' => $user->id,
            'name' => 'Marcus Sterling',
            'bio' => 'A pragmatic storyteller with a gift for dialogue and character development, bringing realism to any narrative.',
            'personality_traits' => ['pragmatic', 'witty', 'observant', 'collaborative'],
            'writing_style' => [
                'tone' => 'conversational',
                'complexity' => 'medium',
                'perspective' => 'first_person',
                'genre_preferences' => ['contemporary', 'literary_fiction', 'drama']
            ],
            'expertise_areas' => ['human_behavior', 'relationships', 'social_dynamics'],
            'interests' => ['street_photography', 'jazz', 'coffee_culture'],
            'preferred_language' => 'en',
            'is_active' => true,
        ]);

        // Create a book project
        $book = Book::create([
            'user_id' => $user->id,
            'title' => 'The Echo Chamber',
            'synopsis' => 'A psychological thriller about a sound engineer who discovers that the abandoned radio station she\'s investigating holds dark secrets that mirror her own forgotten past.',
            'genre' => 'Psychological Thriller',
            'language' => 'en',
            'characters' => [
                'Sophie Chen' => 'Sound engineer and protagonist with hidden trauma',
                'Detective Ray Morrison' => 'Veteran detective investigating disappearances',
                'Dr. Elena Vasquez' => 'Psychiatrist studying memory disorders',
                'The Voice' => 'Mysterious presence from the radio broadcasts'
            ],
            'settings' => [
                'time_period' => 'Contemporary (2024)',
                'primary_location' => 'Abandoned WKRP radio station, Portland',
                'secondary_locations' => ['Sophie\'s apartment', 'Portland Police Department', 'Memory clinic']
            ],
            'themes' => 'Memory, trauma, the power of sound, reality vs. perception, confronting the past',
            'target_word_count' => 80000,
            'current_word_count' => 0,
            'completion_percentage' => 0.00,
            'status' => 'writing',
        ]);

        // Create a conversation
        $conversation = Conversation::create([
            'book_id' => $book->id,
            'title' => 'Opening Chapter Development',
            'topic' => 'Crafting the perfect opening scene that introduces Sophie and the mysterious radio station',
            'context' => 'We need to establish atmosphere, introduce our protagonist, and hint at the supernatural elements without giving too much away.',
            'status' => 'active',
            'participants' => [$author1->id, $author2->id],
            'conversation_settings' => [
                'focus_areas' => ['atmosphere', 'character_introduction', 'pacing'],
                'chapter_target' => 1,
                'word_count_goal' => 2500
            ],
        ]);

        // Create some messages in the conversation
        Message::create([
            'conversation_id' => $conversation->id,
            'author_id' => $author1->id,
            'content' => 'Marcus, I think we should open with Sophie standing outside the radio station at twilight. The building should feel alive with whispers of its past. What if she\'s drawn here not by assignment, but by dreams she can\'t explain?',
            'type' => 'message',
            'context_summary' => 'Discussing opening scene approach',
            'word_count' => 42,
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'author_id' => $author2->id,
            'content' => 'Aria, I love the dreamlike pull, but let\'s ground it in reality first. What if she\'s there for work - maybe doing acoustic analysis for a renovation project - and the supernatural elements creep in gradually? More believable character motivation.',
            'type' => 'message',
            'context_summary' => 'Suggesting realistic motivation',
            'word_count' => 41,
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'author_id' => $author1->id,
            'content' => 'Perfect balance! So Sophie arrives with her equipment, professional and focused. But as she sets up her microphones, she starts picking up sounds that shouldn\'t be there. The building begins to reveal its secrets through sound itself.',
            'type' => 'story_contribution',
            'context_summary' => 'Building on the realistic setup',
            'word_count' => 44,
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'author_id' => $author2->id,
            'content' => 'Here\'s the opening: Sophie calibrates her equipment in the main broadcast booth, the late afternoon sun casting long shadows through grimy windows. That\'s when she hears it - a voice, clear as day, reading the evening news from 1987. But her equipment shows only silence.',
            'type' => 'chapter_draft',
            'context_summary' => 'Concrete opening scene draft',
            'word_count' => 48,
        ]);

        // Update conversation statistics
        $conversation->updateMessageCount();

        // Create first chapter
        Chapter::create([
            'book_id' => $book->id,
            'conversation_id' => $conversation->id,
            'title' => 'The Signal',
            'chapter_number' => 1,
            'content' => 'Sophie Chen pulled her Subaru into the cracked parking lot of WKRP, the abandoned radio station that had been calling to her dreams for weeks. The building loomed against the Portland skyline like a monument to forgotten voices, its transmission tower reaching into the gathering dusk like a finger pointing at secrets in the sky...',
            'summary' => 'Sophie arrives at the abandoned radio station for what should be routine acoustic analysis, but begins experiencing supernatural phenomena.',
            'character_appearances' => ['Sophie Chen'],
            'is_completed' => false,
            'notes' => 'Opening chapter establishing atmosphere and introducing supernatural elements',
            'order_index' => 1,
        ]);

        // Update book word count
        $book->updateWordCount();
    }
}
