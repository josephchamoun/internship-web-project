<nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-500 to-pink-500 border-b">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <div class="w-10 h-10 lg:w-12 lg:h-12">
                        <x-application-logo class="w-full h-full fill-current text-gray-800" />
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:flex lg:items-center space-x-2 ml-2">
                    <x-nav-link :href="url('/dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-store text-xs"></i>
                        <span class="ml-1 text-xs">Store</span>
                    </x-nav-link>

                    @if (Auth::check() && Auth::user()->role === 'Manager')
                    <x-nav-link :href="url('/categories')" :active="request()->routeIs('categories')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-list text-xs"></i>
                        <span class="ml-1 text-xs">Categories</span>
                    </x-nav-link>
                    
                    <x-nav-link :href="url('/itemsupplier')" :active="request()->routeIs('itemsupplier')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-truck text-xs"></i>
                        <span class="ml-1 text-xs">Supplies</span>
                    </x-nav-link>
                    
                    <x-nav-link :href="url('/users')" :active="request()->routeIs('users')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-users text-xs"></i>
                        <span class="ml-1 text-xs">Users</span>
                    </x-nav-link>
                    
                    <x-nav-link :href="url('/suppliers')" :active="request()->routeIs('suppliers')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-industry text-xs"></i>
                        <span class="ml-1 text-xs">Suppliers</span>
                    </x-nav-link>
                    
                    <x-nav-link :href="url('/usersorders')" :active="request()->routeIs('usersorders')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-shopping-cart text-xs"></i>
                        <span class="ml-1 text-xs">Orders</span>
                    </x-nav-link>
                    @endif

                    <x-nav-link :href="url('/myorders')" :active="request()->routeIs('myorders')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-receipt text-xs"></i>
                        <span class="ml-1 text-xs">My Orders</span>
                    </x-nav-link>

                    @if (Auth::check() && Auth::user()->role === 'Manager')
                    <x-nav-link :href="url('/stats')" :active="request()->routeIs('stats')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-chart-line text-xs"></i>
                        <span class="ml-1 text-xs">Stats</span>
                    </x-nav-link>
                    @endif

                    <x-nav-link :href="url('/contact')" :active="request()->routeIs('contact')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-envelope text-xs"></i>
                        <span class="ml-1 text-xs">Contact</span>
                    </x-nav-link>

                    @if (Auth::check() && Auth::user()->role === 'Manager')
                    <x-nav-link :href="url('/messages')" :active="request()->routeIs('messages')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-envelope text-xs"></i>
                        <span class="ml-1 text-xs">Messages</span>
                    </x-nav-link>
                    @endif

                    <x-nav-link :href="url('/about')" :active="request()->routeIs('about')" class="text-white hover:bg-white/20 px-2 py-1.5 rounded-md transition duration-150">
                        <i class="fas fa-info-circle text-xs"></i>
                        <span class="ml-1 text-xs">About</span>
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center">
                <a href="{{ route('cart.view') }}" class="text-white hover:text-gray-200 transition mr-4">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8h12.2M7 13l3-8h8m-4 16a2 2 0 100-4 2 2 0 000 4zm-8 0a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </div>
                </a>
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md text-gray-800 bg-white hover:text-gray-700 focus:outline-none transition duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center lg:hidden">
                <a href="{{ route('cart.view') }}" class="text-white hover:text-gray-200 transition mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8h12.2M7 13l3-8h8m-4 16a2 2 0 100-4 2 2 0 000 4zm-8 0a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </a>
                
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-white/20 focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1 bg-white/10">
        <x-responsive-nav-link :href="url('/dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-store w-6 text-center"></i>
                    <span class="ml-2">{{ __('Store') }}</span>
                </div>
            </x-responsive-nav-link>
            
            @if (Auth::check() && Auth::user()->role === 'Manager')
            <x-responsive-nav-link :href="url('/categories')" :active="request()->routeIs('categories')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-list w-6 text-center"></i>
                    <span class="ml-2">Categories</span>
                </div>
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="url('/itemsupplier')" :active="request()->routeIs('itemsupplier')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-truck w-6 text-center"></i>
                    <span class="ml-2">Supplies</span>
                </div>
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="url('/users')" :active="request()->routeIs('users')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span class="ml-2">Users</span>
                </div>
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="url('/suppliers')" :active="request()->routeIs('suppliers')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-industry w-6 text-center"></i>
                    <span class="ml-2">Suppliers</span>
                </div>
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="url('/usersorders')" :active="request()->routeIs('usersorders')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-shopping-cart w-6 text-center"></i>
                    <span class="ml-2">Orders</span>
                </div>
            </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="url('/myorders')" :active="request()->routeIs('myorders')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-receipt w-6 text-center"></i>
                    <span class="ml-2">My Orders</span>
                </div>
            </x-responsive-nav-link>

            @if (Auth::check() && Auth::user()->role === 'Manager')
            <x-responsive-nav-link :href="url('/stats')" :active="request()->routeIs('stats')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-chart-line w-6 text-center"></i>
                    <span class="ml-2">Stats</span>
                </div>
            </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="url('/contact')" :active="request()->routeIs('contact')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-envelope w-6 text-center"></i>
                    <span class="ml-2">Contact</span>
                </div>
            </x-responsive-nav-link>

            @if (Auth::check() && Auth::user()->role === 'Manager')
            <x-responsive-nav-link :href="url('/messages')" :active="request()->routeIs('messages')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-envelope w-6 text-center"></i>
                    <span class="ml-2">Messages</span>
                </div>
            </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="url('/about')" :active="request()->routeIs('about')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                <div class="flex items-center">
                    <i class="fas fa-info-circle w-6 text-center"></i>
                    <span class="ml-2">About</span>
                </div>
            </x-responsive-nav-link>
            
            <!-- Rest of the mobile menu items remain the same -->
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-white/20">
            <div class="px-4">
                <div class="font-medium text-sm text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-xs text-white/80">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                    <div class="flex items-center">
                        <i class="fas fa-user w-6 text-center"></i>
                        <span class="ml-2">{{ __('Profile') }}</span>
                    </div>
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();" 
                            class="text-white hover:bg-white/20 block px-3 py-2 rounded-md text-sm font-medium transition duration-150">
                        <div class="flex items-center">
                            <i class="fas fa-sign-out-alt w-6 text-center"></i>
                            <span class="ml-2">{{ __('Log Out') }}</span>
                        </div>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>