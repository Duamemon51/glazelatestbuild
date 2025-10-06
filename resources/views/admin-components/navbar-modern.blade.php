<!-- Top Navigation -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Mobile menu button -->
        <button @click="sidebarOpen = !sidebarOpen; mobileMenuOpen = !sidebarOpen"
                class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Breadcrumb -->
        <div class="hidden lg:block">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-700 font-medium">@yield('title', 'Dashboard')</span>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 relative">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
            </button>

            <!-- User menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <span class="hidden md:block">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>

                <div x-show="open" @click.away="open = false"
                     x-transition
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2"></i>Settings
                    </a>
                    <hr class="my-1">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>