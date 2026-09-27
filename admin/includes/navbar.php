<header class="admin-navbar">

    <div class="navbar-left">

        <button class="sidebar-toggle" id="sidebarToggle">
            ☰
        </button>

        <div>

            <h1><?= htmlspecialchars($page_title) ?></h1>

            <p>Pharaohs Integrated Business</p>

        </div>

    </div>


    <div class="navbar-right">

        <div class="admin-user">

            <div class="admin-avatar">
                <?= strtoupper(substr($_SESSION["admin_name"], 0, 1)) ?>
            </div>

            <div class="admin-user-info">

                <strong>
                    <?= htmlspecialchars($_SESSION["admin_name"]) ?>
                </strong>

                <span>
                    <?= htmlspecialchars($_SESSION["admin_role"]) ?>
                </span>

            </div>

        </div>

    </div>

</header>