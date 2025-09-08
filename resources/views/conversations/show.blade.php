<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $conversation->title }} - Author Conversations</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen neural-bg">
    <!-- Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-neural-500/10 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute top-3/4 right-1/4 w-96 h-96 bg-quantum-500/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-1/4 left-1/3 w-80 h-80 bg-cyber-500/10 rounded-full blur-3xl animate-bounce-slow"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-50 px-6 py-4 border-b border-white/10">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-neural-500 to-cyber-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-glow">Author Conversations</span>
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-300">{{ $conversation->title }}</span>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="/admin" class="text-gray-300 hover:text-white transition-colors">Admin Panel</a>
                <div class="text-gray-300">👤 {{ auth()->user()->name }}</div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 py-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg text-red-300">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Conversation Header -->
        <div class="glass rounded-2xl p-8 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-glow mb-2">{{ $conversation->title }}</h1>
                    <p class="text-gray-300 text-lg">{{ $conversation->topic ?? 'No topic specified' }}</p>
                </div>
                
                <div class="flex items-center space-x-4 mt-4 lg:mt-0">
                    <!-- Status Badge -->
                    <div class="px-4 py-2 rounded-full text-sm font-semibold 
                        @if($conversation->status === 'active') bg-green-500/20 text-green-300 
                        @elseif($conversation->status === 'paused') bg-yellow-500/20 text-yellow-300
                        @elseif($conversation->status === 'completed') bg-blue-500/20 text-blue-300
                        @else bg-gray-500/20 text-gray-300 @endif">
                        {{ ucfirst($conversation->status) }}
                        @if($conversation->isPaused())
                            🔄
                        @endif
                    </div>

                    <!-- Human Interaction Badge -->
                    @if($conversation->allows_human_interaction)
                        <div class="px-4 py-2 rounded-full text-sm font-semibold bg-neural-500/20 text-neural-300">
                            👤 Human Interaction Enabled
                        </div>
                    @endif
                </div>
            </div>

            <!-- Participants -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-200 mb-3">AI Authors</h3>
                    <div class="space-y-2">
                        @forelse($aiAuthors as $author)
                            <div class="flex items-center space-x-3 p-3 bg-white/5 rounded-lg">
                                <div class="w-8 h-8 bg-neural-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">🤖</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-white">{{ $author->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $author->getPersonalityString() }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-400">No AI authors assigned</div>
                        @endforelse
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-200 mb-3">Human Participants</h3>
                    <div class="space-y-2">
                        @forelse($humanParticipants as $participant)
                            <div class="flex items-center space-x-3 p-3 bg-white/5 rounded-lg">
                                <div class="w-8 h-8 bg-quantum-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">👤</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-white">{{ $participant->name }}</div>
                                    <div class="text-sm text-gray-400">Human participant</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-400">No human participants</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4">
                @if(!in_array(auth()->id(), $conversation->human_participants ?? []))
                    <form method="POST" action="{{ route('conversations.join', $conversation) }}">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-neural-500 to-quantum-500 text-white font-semibold rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                            👤 Join as Human
                        </button>
                    </form>
                @endif

                @if($conversation->status === 'active' && in_array(auth()->id(), $conversation->human_participants ?? []))
                    <form method="POST" action="{{ route('conversations.pause', $conversation) }}">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300"
                                onclick="return confirm('Are you sure you want to pause this conversation?')">
                            🔄 Pause Conversation
                        </button>
                    </form>
                @endif

                @if($conversation->status === 'paused' && in_array(auth()->id(), $conversation->human_participants ?? []))
                    <form method="POST" action="{{ route('conversations.resume', $conversation) }}">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300"
                                onclick="return confirm('Are you sure you want to resume this conversation?')">
                            ▶️ Resume Conversation
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Messages -->
        <div class="glass rounded-2xl p-8 mb-8">
            <h2 class="text-2xl font-bold text-glow mb-6">Conversation Messages</h2>
            
            <div class="space-y-6 max-h-96 overflow-y-auto">
                @forelse($messages as $message)
                    <div class="flex space-x-4 @if($message->isHumanMessage()) flex-row-reverse space-x-reverse @endif">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                @if($message->isHumanMessage()) bg-quantum-500 @else bg-neural-500 @endif">
                                <span class="text-white font-bold">
                                    @if($message->isHumanMessage()) 👤 @else 🤖 @endif
                                </span>
                            </div>
                        </div>

                        <!-- Message Content -->
                        <div class="flex-1 max-w-lg">
                            <div class="@if($message->isHumanMessage()) bg-quantum-500/20 border-quantum-500/30 @else bg-neural-500/20 border-neural-500/30 @endif border rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-semibold text-white">
                                        {{ $message->getSenderName() }}
                                    </div>
                                    <div class="text-xs 
                                        @if($message->type === 'human_pause') text-yellow-300
                                        @elseif($message->type === 'human_resume') text-green-300
                                        @elseif($message->type === 'human_input') text-blue-300
                                        @elseif($message->type === 'story_contribution') text-purple-300
                                        @else text-gray-300 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $message->type)) }}
                                    </div>
                                </div>
                                <div class="text-gray-200 whitespace-pre-wrap">{{ $message->content }}</div>
                                <div class="text-xs text-gray-400 mt-2">
                                    {{ $message->created_at->format('M j, Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 py-12">
                        <div class="text-4xl mb-4">💬</div>
                        <div>No messages yet. Start the conversation!</div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Message Input (only for participants) -->
        @if(in_array(auth()->id(), $conversation->human_participants ?? []))
            <div class="glass rounded-2xl p-8">
                <h3 class="text-xl font-bold text-glow mb-4">Add Your Message</h3>
                
                <form method="POST" action="{{ route('conversations.messages.store', $conversation) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-semibold text-gray-200 mb-2">Message Type</label>
                        <select name="type" id="type" class="w-full p-3 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-neural-500">
                            <option value="human_input">General Input</option>
                            <option value="story_contribution">Story Contribution</option>
                            <option value="revision_suggestion">Revision Suggestion</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="content" class="block text-sm font-semibold text-gray-200 mb-2">Your Message</label>
                        <textarea name="content" id="content" rows="6" 
                                class="w-full p-4 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-neural-500"
                                placeholder="Write your message to the AI authors..."
                                required></textarea>
                    </div>

                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-neural-500 to-cyber-500 text-white font-semibold rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        💬 Send Message
                    </button>
                </form>
            </div>
        @endif
    </div>
</body>
</html>