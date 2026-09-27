<aside class="sidebar">

    <div class="sidebar-logo">

        <h2>PHARAOHS</h2>

        <span>Integrated Business</span>

    </div>

    <nav class="sidebar-menu">

        <div class="menu-title">
            MAIN
        </div>

        <a href="<?= ADMIN_URL ?>index.php"
           class="menu-item <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">

            <span class="menu-icon">⌂</span>

            <span>Dashboard</span>

        </a>


        <div class="menu-title">
            CONTENT
        </div>

        <a href="<?= ADMIN_URL ?>services/index.php" class="menu-item">

            <span class="menu-icon">▣</span>

            <span>Services</span>

        </a>


        <a href="<?= ADMIN_URL ?>industries/index.php" class="menu-item">

            <span class="menu-icon">◈</span>

            <span>Industries</span>

        </a>


        <a href="<?= ADMIN_URL ?>categories/index.php" class="menu-item">

            <span class="menu-icon">▤</span>

            <span>Categories</span>

        </a>


        <a href="<?= ADMIN_URL ?>products/index.php" class="menu-item">

            <span class="menu-icon">□</span>

            <span>Products</span>

        </a>


        <a href="<?= ADMIN_URL ?>investments/index.php" class="menu-item">

            <span class="menu-icon">◆</span>

            <span>Investments</span>

        </a>


        <div class="menu-title">
            REQUESTS
        </div>


        <a href="<?= ADMIN_URL ?>quotes/index.php" class="menu-item">

            <span class="menu-icon">✉</span>

            <span>Quote Requests</span>

        </a>


        <a href="<?= ADMIN_URL ?>contacts/index.php" class="menu-item">

            <span class="menu-icon">☏</span>

            <span>Messages</span>

        </a>


        <div class="menu-title">
            SYSTEM
        </div>


        <a href="<?= ADMIN_URL ?>settings/index.php" class="menu-item">

            <span class="menu-icon">⚙</span>

            <span>Settings</span>

        </a>


        <a href="<?= ADMIN_URL ?>logout.php" class="menu-item logout-item">

            <span class="menu-icon">↪</span>

            <span>Logout</span>

        </a>

    </nav>

</aside>