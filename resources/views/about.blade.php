<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('About') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
        <div class="container mx-auto px-4 max-w-5xl">
            <!-- About Section -->
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl mb-12 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                <!-- Decorative header -->
                <div class="h-3 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
                
                <div class="p-8 md:p-10">
                    <div class="flex items-center justify-center mb-8">
                        <div class="bg-gradient-to-r from-blue-600 to-pink-600 text-white text-2xl font-bold py-3 px-8 rounded-full shadow-lg">
                            About Our Platform
                        </div>
                    </div>
                    
                    <p class="text-gray-700 text-lg leading-relaxed mb-8 text-center max-w-3xl mx-auto">
                        This website was created as part of the XpertBot internship program for the web project. It is designed as an e-commerce platform that caters to gaming enthusiasts, offering a wide variety of game types to suit all preferences. From action-packed adventures to immersive role-playing games, our goal is to provide a seamless shopping experience for gamers of all interests. Explore our collection and find the perfect game for your next adventure!
                    </p>
                    
                    <!-- Gaming icons -->
                    <div class="flex justify-center space-x-6 mb-6">
                        <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                            </svg>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center bg-purple-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="w-12 h-12 flex items-center justify-center bg-pink-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Developers Section -->
            <div class="relative overflow-hidden bg-white rounded-2xl shadow-xl transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                <!-- Decorative header -->
                <div class="h-3 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
                
                <div class="p-8 md:p-10">
                    <div class="flex items-center justify-center mb-10">
                        <div class="bg-gradient-to-r from-blue-600 to-pink-600 text-white text-2xl font-bold py-3 px-8 rounded-full shadow-lg">
                            Meet Our Developers
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-3xl mx-auto">
                        <!-- Developer 1 -->
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-40 h-40 rounded-full overflow-hidden mb-6 shadow-lg border-4 border-white transition-transform duration-300 group-hover:scale-105">
                                <img src="{{ asset('storage/images/joseph.jpg') }}" alt="Joseph Chamoun" class="w-full h-full object-cover">
                            </div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Joseph Chamoun</h4>
                            <p class="text-gray-500 mb-4">Full Stack Developer</p>
                            <div class="flex space-x-3">
                                

                            <a href="https://www.linkedin.com/in/joseph-chamoun-7700b2334/" class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center transition-colors duration-300 hover:bg-blue-700 hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11 19h-3v-10h3v10zm-1.5-11.268c-.966 0-1.75-.784-1.75-1.75s.784-1.75 1.75-1.75 1.75.784 1.75 1.75-.784 1.75-1.75 1.75zm13.5 11.268h-3v-5.5c0-1.378-1.122-2.5-2.5-2.5s-2.5 1.122-2.5 2.5v5.5h-3v-10h3v1.5c.878-1.314 2.5-1.5 3.5-1.5 2.485 0 4.5 2.015 4.5 4.5v5.5z"/>
                                </svg>
                            </a>
                            <a href="https://github.com/josephchamoun" class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center transition-colors duration-300 hover:bg-gray-800 hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            </div>
                        </div>
                        
                        <!-- Developer 2 -->
                        <div class="flex flex-col items-center text-center group">
                            <div class="w-40 h-40 rounded-full overflow-hidden mb-6 shadow-lg border-4 border-white transition-transform duration-300 group-hover:scale-105">
                                <img src="{{ asset('storage/images/rodrique.jpg') }}" alt="Rodrique Khoury" class="w-full h-full object-cover">
                            </div>
                            <h4 class="text-xl font-bold text-gray-800 mb-2">Rodrique Khoury</h4>
                            <p class="text-gray-500 mb-4">Full Stack Developer</p>
                            <div class="flex space-x-3">
                                
                            <a href="https://www.linkedin.com/in/your-profile" class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center transition-colors duration-300 hover:bg-blue-700 hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11 19h-3v-10h3v10zm-1.5-11.268c-.966 0-1.75-.784-1.75-1.75s.784-1.75 1.75-1.75 1.75.784 1.75 1.75-.784 1.75-1.75 1.75zm13.5 11.268h-3v-5.5c0-1.378-1.122-2.5-2.5-2.5s-2.5 1.122-2.5 2.5v5.5h-3v-10h3v1.5c.878-1.314 2.5-1.5 3.5-1.5 2.485 0 4.5 2.015 4.5 4.5v5.5z"/>
                                </svg>
                            </a>
                            <a href="https://github.com/your-profile" class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center transition-colors duration-300 hover:bg-gray-800 hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gaming quote section -->
            <div class="mt-12 text-center">
                <blockquote class="italic text-xl text-gray-600 max-w-3xl mx-auto">
                    "In the world of games, we don't just play — we live countless lives, experience infinite adventures, and forge unforgettable memories."
                </blockquote>
            </div>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script><!--heda daroure ta ye5od l design-->
</x-app-layout>