<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Author Conversations') }} - Revolutionary AI-Powered Collaborative Book Writing</title>
        <meta name="description" content="The world's first AI-powered platform for collaborative book writing through intelligent author conversations. Create, collaborate, and publish with AI authors.">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen neural-bg">
        <!-- Neural Network Background Animation -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-neural-500/10 rounded-full blur-3xl animate-pulse-slow"></div>
            <div class="absolute top-3/4 right-1/4 w-96 h-96 bg-quantum-500/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-1/4 left-1/3 w-80 h-80 bg-cyber-500/10 rounded-full blur-3xl animate-bounce-slow"></div>
        </div>
        
        <!-- Navigation -->
        <nav class="relative z-50 px-6 py-4" x-data="{ mobileOpen: false }">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-neural-500 to-cyber-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-glow">Author Conversations</h1>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-300 hover:text-neural-400 transition-colors">Features</a>
                    <a href="#how-it-works" class="text-gray-300 hover:text-quantum-400 transition-colors">How It Works</a>
                    <a href="#pricing" class="text-gray-300 hover:text-cyber-400 transition-colors">Pricing</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-neural-400 hover:text-neural-300 font-semibold">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">Log In</a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-neural-500 to-cyber-500 text-white px-6 py-2 rounded-full hover:shadow-lg transform hover:scale-105 transition-all duration-300">Get Started</a>
                    @endauth
                </div>
                
                <!-- Mobile menu button -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </nav>
        
        <!-- Hero Section -->
        <section class="relative z-10 px-6 py-20">
            <div class="max-w-7xl mx-auto text-center">
                <h2 class="text-6xl md:text-8xl font-bold mb-8 text-glow leading-tight">
                    Where AI Creativity
                    <br>
                    <span class="bg-gradient-to-r from-neural-400 via-quantum-400 to-cyber-400 bg-clip-text text-transparent">
                        Meets Human Imagination
                    </span>
                </h2>
                
                <p class="text-xl md:text-2xl text-gray-300 mb-12 max-w-4xl mx-auto leading-relaxed">
                    The world's first AI-powered platform for collaborative book writing. 
                    Create fictional authors, develop rich personalities, and watch them craft 
                    extraordinary stories through intelligent conversations.
                </p>
                
                <div class="flex flex-col md:flex-row gap-6 justify-center items-center mb-16">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="group relative px-12 py-4 bg-gradient-to-r from-neural-500 to-cyber-500 text-white font-bold text-lg rounded-full hover:shadow-2xl transform hover:scale-105 transition-all duration-300 neural-glow">
                            <span class="relative z-10">Open Dashboard</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-neural-400 to-cyber-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur-sm"></div>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="group relative px-12 py-4 bg-gradient-to-r from-neural-500 to-cyber-500 text-white font-bold text-lg rounded-full hover:shadow-2xl transform hover:scale-105 transition-all duration-300 neural-glow">
                            <span class="relative z-10">Start Creating</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-neural-400 to-cyber-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur-sm"></div>
                        </a>
                    @endauth
                    
                    <a href="/admin" class="px-12 py-4 glass text-white font-bold text-lg rounded-full hover:shadow-2xl transform hover:scale-105 transition-all duration-300 border border-white/20">
                        Admin Panel
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    <div class="glass rounded-2xl p-8 transform hover:scale-105 transition-all duration-300">
                        <div class="text-4xl font-bold text-neural-400 mb-2">∞</div>
                        <div class="text-white font-semibold">Infinite Possibilities</div>
                        <div class="text-gray-400 text-sm">AI-powered creativity</div>
                    </div>
                    <div class="glass rounded-2xl p-8 transform hover:scale-105 transition-all duration-300">
                        <div class="text-4xl font-bold text-quantum-400 mb-2">24/7</div>
                        <div class="text-white font-semibold">Always Available</div>
                        <div class="text-gray-400 text-sm">Never-sleeping authors</div>
                    </div>
                    <div class="glass rounded-2xl p-8 transform hover:scale-105 transition-all duration-300">
                        <div class="text-4xl font-bold text-cyber-400 mb-2">100%</div>
                        <div class="text-white font-semibold">Your Content</div>
                        <div class="text-gray-400 text-sm">Full ownership rights</div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- CTA Section -->
        <section class="relative z-10 px-6 py-20">
            <div class="max-w-4xl mx-auto text-center">
                <div class="glass rounded-3xl p-12">
                    <h3 class="text-4xl font-bold mb-6 text-glow">Ready to revolutionize your writing?</h3>
                    <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                        Join the future of collaborative storytelling. Create your first AI author and 
                        start crafting extraordinary narratives today.
                    </p>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-block px-12 py-4 bg-gradient-to-r from-neural-500 to-cyber-500 text-white font-bold text-lg rounded-full hover:shadow-2xl transform hover:scale-105 transition-all duration-300 neural-glow">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-block px-12 py-4 bg-gradient-to-r from-neural-500 to-cyber-500 text-white font-bold text-lg rounded-full hover:shadow-2xl transform hover:scale-105 transition-all duration-300 neural-glow">
                            Get Started Free
                        </a>
                    @endauth
                </div>
            </div>
        </section>
        
        <!-- Footer -->
        <footer class="relative z-10 px-6 py-12 border-t border-white/10">
            <div class="max-w-7xl mx-auto text-center">
                <div class="flex items-center justify-center space-x-2 mb-6">
                    <div class="w-8 h-8 bg-gradient-to-br from-neural-500 to-cyber-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-glow">Author Conversations</span>
                </div>
                <p class="text-gray-400 mb-8">
                    Where AI creativity meets human imagination to craft the stories of tomorrow.
                </p>
                <div class="text-sm text-gray-500">
                    © {{ date('Y') }} Author Conversations. All rights reserved. • Privacy-first design • Full content ownership
                </div>
            </div>
        </footer>
    </body>
</html>