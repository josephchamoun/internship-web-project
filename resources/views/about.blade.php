<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('About') }}
        </h2>
    </x-slot>


    <div class="bg-gradient-to-r from-blue-200 to-pink-200 min-h-screen">
        <div class="container mx-auto py-8 px-4">
            <!-- About Section -->
            <div class="bg-gradient-to-r from-blue-600 to-pink-600 shadow-2xl rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4 text-center text-white">About</h2>
                <p class="text-gray-300 text-lg mb-6">
                    This website was created as part of the XpertBot internship program for the web project. It is designed as an e-commerce platform that caters to gaming enthusiasts, offering a wide variety of game types to suit all preferences. From action-packed adventures to immersive role-playing games, our goal is to provide a seamless shopping experience for gamers of all interests. Explore our collection and find the perfect game for your next adventure!
                </p>
            </div>

            <!-- Developers Section -->
            <div class="bg-gradient-to-r from-blue-600 to-pink-600 shadow-2xl rounded-lg p-6">
                <h3 class="text-xl font-bold mb-6 text-center text-white">Developers</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Developer 1 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-32 h-32 rounded-full overflow-hidden mb-4">
                        <img src="{{ asset('storage/images/joseph.jpg') }}" alt="Developer 1" class="w-full h-full object-cover">
                        </div>
                        <h4 class="text-lg font-semibold text-white">Joseph Chamoun</h4>
                    </div>
                    <!-- Developer 2 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-32 h-32 rounded-full overflow-hidden mb-4">
                            <img src="{{ asset('storage/images/rodrique.jpg') }}" alt="Developer 2" class="w-full h-full object-cover">
                        </div>
                        <h4 class="text-lg font-semibold text-white">Rodrique Khoury</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.tailwindcss.com"></script><!--heda daroure ta ye5od l design-->

</x-app-layout>