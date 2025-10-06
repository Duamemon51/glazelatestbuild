<nav class="bg-white shadow-lg w-64 fixed inset-y-0 left-0 z-50 transform lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out"
     :class="{ '-translate-x-full': !sidebarOpen }"
     x-show="sidebarOpen || window.innerWidth >= 1024">

    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-gradient-to-r from-blue-600 to-purple-600">
        <h1 class="text-white text-xl font-bold">Admin Panel</h1>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-2 px-4">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Products -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-box mr-3"></i>
                        <span>Products</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                    <a href="{{ route('admin.products.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.products.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                        All Products
                    </a>
                </div>
            </li>

            <!-- Categories -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-sitemap mr-3"></i>
                        <span>Categories</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                    <a href="{{ route('admin.parents.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.parents.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                        Main Categories
                    </a>
                    <a href="{{ route('admin.product-types.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.product-types.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                        Product Types
                    </a>
                    <a href="{{ route('admin.subcategories.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.subcategories.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                        Subcategories
                    </a>
                </div>
            </li>

            <!-- Designs -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-palette mr-3"></i>
                        <span>Designs</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                    <a href="{{ route('admin.designs.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.designs.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                        All Designs
                    </a>
                </div>
            </li>

            <!-- Color Management -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-paint-brush mr-3"></i>
                        <span>Color Management</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                    <a href="{{ route('admin.color-categories.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.color-categories.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                        Color Categories
                    </a>
                    <a href="{{ route('admin.colors.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.colors.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                        Colors
                    </a>
                </div>
            </li>

            <!-- Patterns -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-shapes mr-3"></i>
                        <span>Patterns</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                    <a href="{{ route('admin.patterns.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.patterns.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                        All Patterns
                    </a>
                </div>
            </li>

            <!-- Examples -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-image mr-3"></i>
                        <span>Examples</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                    <a href="{{ route('admin.examples.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.examples.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                        All Examples
                    </a>
                </div>
            </li>

            <!-- Users -->
            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    <span>Users</span>
                </a>
            </li>

            <!-- Orders -->
            <li>
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    <span>Orders</span>
                </a>
            </li>

            <!-- Reports -->
            <li>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Reports</span>
                </a>
            </li>

            <!-- CMS Management -->
            <li x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        <span>Content Management</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" x-transition class="mt-2 ml-6 space-y-1">
                    <a href="{{ route('admin.cms.menus.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.cms.menus.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                        Menus
                    </a>
                    <a href="{{ route('admin.cms.pages.index') }}"
                       class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 {{ request()->routeIs('admin.cms.pages.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                        Pages
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>