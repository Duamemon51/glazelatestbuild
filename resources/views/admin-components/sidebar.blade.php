<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse show" id="sidebarMenu">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link text-white-50" aria-current="page" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home me-2"></i>
                    Dashboard
                </a>
            </li>

            <!-- Users -->
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users me-2"></i>
                    Users
                </a>
            </li>

           <!-- Products -->
<li class="nav-item">
    <a class="nav-link text-white-50 collapsed" href="#productsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="productsSubmenu">
        <i class="fas fa-box-open me-2"></i>
        Products
    </a>
    <div class="collapse" id="productsSubmenu">
        <ul class="nav flex-column ms-3">
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-list-alt me-2"></i>
                    All Products
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Parents -->
<li class="nav-item">
    <a class="nav-link text-white-50 collapsed" href="#parentsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="parentsSubmenu">
        <i class="fas fa-sitemap me-2"></i>
        Main Category
    </a>
    <div class="collapse" id="parentsSubmenu">
        <ul class="nav flex-column ms-3">
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.parents.index') }}">
                    <i class="fas fa-list me-2"></i>
                    All Main Categories
                </a>
            </li>
        </ul>
    </div>
</li>
<!-- Product Types -->
<li class="nav-item">
    <a class="nav-link text-white-50 collapsed" href="#productTypesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="productTypesSubmenu">
        <i class="fas fa-cubes me-2"></i>
        Product Types
    </a>
    <div class="collapse" id="productTypesSubmenu">
        <ul class="nav flex-column ms-3">
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.product-types.index') }}">
                    <i class="fas fa-list me-2"></i>
                    All Product Types
                </a>
            </li>
        </ul>
    </div>
</li>

    <!-- Subcategories -->
<li class="nav-item">
    <a class="nav-link text-white-50 collapsed" href="#subcategoriesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="subcategoriesSubmenu">
        <i class="fas fa-layer-group me-2"></i>
        Subcategories
    </a>
    <div class="collapse" id="subcategoriesSubmenu">
        <ul class="nav flex-column ms-3">
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.subcategories.index') }}">
                    <i class="fas fa-list me-2"></i>
                    All Subcategories
                </a>
            </li>
        </ul>
    </div>
</li>


<li class="nav-item">
                <a class="nav-link text-white-50 collapsed" href="#examplesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="examplesSubmenu">
                    <i class="fas fa-image me-2"></i>
                    Examples
                </a>
                <div class="collapse" id="examplesSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('admin.examples.index') }}">
                                <i class="fas fa-list me-2"></i>
                                All Examples
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
<!-- Designs -->
<li class="nav-item">
    <a class="nav-link text-white-50 collapsed" href="#designsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="designsSubmenu">
        <i class="fas fa-palette me-2"></i>
        Designs
    </a>
    <div class="collapse" id="designsSubmenu">
        <ul class="nav flex-column ms-3">
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.designs.index') }}">
                    <i class="fas fa-list me-2"></i>
                    All Designs
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Patterns -->
<li class="nav-item">
    <a class="nav-link text-white-50 collapsed" href="#patternsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="patternsSubmenu">
        <i class="fas fa-shapes me-2"></i>
        Patterns
    </a>
    <div class="collapse" id="patternsSubmenu">
        <ul class="nav flex-column ms-3">
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.patterns.index') }}">
                    <i class="fas fa-list me-2"></i>
                    All Patterns
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- Color Management -->
<li class="nav-item">
    <a class="nav-link text-white-50 collapsed" href="#colorsSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="colorsSubmenu">
        <i class="fas fa-paint-brush me-2"></i>
        Color Management
    </a>
    <div class="collapse" id="colorsSubmenu">
        <ul class="nav flex-column ms-3">
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.color-categories.index') }}">
                    <i class="fas fa-folder me-2"></i>
                    Color Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.colors.index') }}">
                    <i class="fas fa-palette me-2"></i>
                    Colors
                </a>
            </li>
        </ul>
    </div>
</li>

            <!-- Orders -->
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Orders
                </a>
            </li>

            <!-- Reports -->
            <li class="nav-item">
                <a class="nav-link text-white-50" href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-chart-line me-2"></i>
                    Reports
                </a>
            </li>
        </ul>
    </div>
</nav>
