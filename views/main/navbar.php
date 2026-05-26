<?php
    if(sizeof($_GET)==0){
        $current_page = 'main';
        $current_controller = 'home';
        $current_action = 'index';
    }else{
        $current_page = isset($_GET['page']) ? $_GET['page'] : '';
        $current_controller = isset($_GET['controller']) ? $_GET['controller'] : '';
        $current_action = isset($_GET['action']) ? $_GET['action'] : '';
    }
    if(isset($_SESSION['username'])){
        $cart = Cart::getCartByUsername($_SESSION['username']);

        // If no cart row exists yet (fresh account), create one automatically
        if($cart === null){
            Cart::createCart($_SESSION['username']);
            $cart = Cart::getCartByUsername($_SESSION['username']);
        }

        $list_item = [];
        $total     = 0;

        if($cart !== null){
            $cartItems = CartItem::getCartItemsByCartId($cart->getCartId());
            foreach($cartItems as $item){
                $prodItem = Product::getProductById($item->getProductId());
                $list_item[] = [
                    'cartItemId'  => $item->getCartItemId(),
                    'productId'   => $prodItem->getProductId(),
                    'productName' => $prodItem->getProductName(),
                    'price'       => $prodItem->getPrice(),
                    'image'       => $prodItem->getImageUrl(),
                    'quantity'    => $item->getQuantity(),
                    'size'        => $item->getSize()
                ];
                $total += $prodItem->getPrice() * $item->getQuantity();
            }
        }

        $user = User::getUserByUsername($_SESSION['username']);
        $data += array('cartItems' => $list_item, 'total' => $total, 'user' => $user);
    }
    $folderPath = 'views/'.$current_page.'/'.$current_controller;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BKSHOP Co. — Premium Fashion</title>

    <!-- External resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Design system (loads Google Fonts + tokens) -->
    <link rel="stylesheet" href="public/css/design-system.css">
    <!-- Page-specific CSS -->
    <link rel="stylesheet" href="<?php echo $folderPath . '/style.css' ?>">
    <!-- Animations -->
    <link rel="stylesheet" href="public/css/animations.css">
    <link rel="stylesheet" href="public/css/image-zoom.css">
</head>

<body>

<!-- ╔══════════════════════════════╗
     ║   ANNOUNCEMENT BAR          ║
     ╚══════════════════════════════╝ -->
<div class="announcement-bar">
    <p>Free shipping on orders over 500,000 VND &nbsp;·&nbsp; New collection now available — <a href="index.php?page=main&controller=products&action=index" style="color:#fff;text-decoration:underline;text-underline-offset:3px;">Shop Now</a></p>
</div>

<!-- ╔══════════════════════════════╗
     ║   SITE HEADER               ║
     ╚══════════════════════════════╝ -->
<header class="site-header" id="siteHeader">
    <div class="header-inner">

        <!-- ── LEFT: hamburger + primary nav ── -->
        <div class="header-left">
            <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
            <nav class="primary-nav" aria-label="Main navigation">
                <a href="index.php?page=main&controller=home&action=index"
                   class="nav-item <?= ($current_controller==='home')    ? 'active':'' ?>">Home</a>
                <a href="index.php?page=main&controller=about&action=index"
                   class="nav-item <?= ($current_controller==='about')   ? 'active':'' ?>">About</a>
                <a href="index.php?page=main&controller=products&action=index"
                   class="nav-item <?= ($current_controller==='products')? 'active':'' ?>">Shop</a>
                <a href="index.php?page=main&controller=blog&action=index"
                   class="nav-item <?= ($current_controller==='blog')    ? 'active':'' ?>">Blog</a>
                <a href="index.php?page=main&controller=contact&action=index"
                   class="nav-item <?= ($current_controller==='contact') ? 'active':'' ?>">Contact</a>
            </nav>
        </div>

        <!-- ── CENTER: Logo ── -->
        <div class="header-center">
            <a href="index.php" class="site-logo">BKSHOP</a>
        </div>

        <!-- ── RIGHT: search · account · cart ── -->
        <div class="header-right">

            <!-- Search trigger -->
            <button class="header-action" id="searchToggle" aria-label="Open search">
                <i class="fas fa-search"></i>
            </button>

            <!-- Account -->
            <?php if (!isset($_SESSION['username'])): ?>
                <a href="index.php?page=main&controller=login&action=index"
                   class="header-action" aria-label="Sign in">
                    <i class="fas fa-user"></i>
                </a>
            <?php else: ?>
                <div class="account-dropdown">
                    <button class="header-action" aria-label="My account">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="account-menu">
                        <a href="index.php?page=main&controller=account&action=index">
                            <i class="fas fa-user-circle" style="margin-right:8px;opacity:.6;"></i>My Account
                        </a>
                        <a href="index.php?page=main&controller=login&action=logout">
                            <i class="fas fa-sign-out-alt" style="margin-right:8px;opacity:.6;"></i>Sign Out
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Cart trigger -->
            <?php if(isset($_SESSION['username'])): ?>
                <button class="header-action" id="cartToggle" aria-label="Open cart">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </button>
            <?php else: ?>
                <a href="index.php?page=main&controller=login&action=index"
                   class="header-action" aria-label="Cart (sign in required)">
                    <i class="fas fa-shopping-bag"></i>
                </a>
            <?php endif; ?>
        </div>

    </div><!-- /header-inner -->

    <!-- ══ SEARCH OVERLAY ══ -->
    <div class="search-overlay" id="searchOverlay" role="dialog" aria-label="Search">
        <div class="search-overlay-inner">
            <form class="search-form" onsubmit="return false;">
                <input type="search" class="search-input"
                       placeholder="Search products…" id="searchInput"
                       autocomplete="off">
                <button type="submit" class="search-submit-btn" aria-label="Submit search">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
            <button class="search-close-btn" id="searchClose" aria-label="Close search">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

</header><!-- /site-header -->

<!-- ╔══════════════════════════════╗
     ║   CART PANEL                ║
     ╚══════════════════════════════╝ -->
<?php if(isset($_SESSION['username'])): ?>
<div class="cart-overlay" id="cartOverlay"></div>
<aside class="cart-panel" id="cartPanel" aria-label="Shopping bag">
    <div class="cart-panel-header">
        <span>Your Bag</span>
        <button class="cart-close-btn" id="cartClose" aria-label="Close cart">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="cart-panel-items" id="product-list">
        <!-- Rendered by JS -->
    </div>
    <div class="cart-panel-footer">
        <div class="cart-total-row">
            <span>Subtotal</span>
            <strong id="total_price">0 VNĐ</strong>
        </div>
        <a href="index.php?page=main&controller=cart&action=index"
           class="btn-full-black">View Full Bag</a>
        <a href="index.php?page=main&controller=cart&action=payment"
           class="btn-full-accent">Checkout</a>
        <p style="text-align:center;font-size:.7rem;color:var(--clr-500);letter-spacing:.06em;margin-top:var(--sp-3);">
            <i class="fas fa-lock" style="margin-right:4px;"></i>Secure checkout
        </p>
    </div>
</aside>
<?php endif; ?>

<!-- ╔══════════════════════════════╗
     ║   MOBILE NAV DRAWER         ║
     ╚══════════════════════════════╝ -->
<div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
<div class="mobile-nav-drawer" id="mobileNavDrawer" aria-label="Mobile navigation">
    <div class="mobile-nav-header">
        <span class="site-logo" style="font-size:1.25rem;">BKSHOP</span>
        <button class="mobile-nav-close" id="mobileNavClose" aria-label="Close menu">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <nav class="mobile-nav-links">
        <a href="index.php?page=main&controller=home&action=index">Home</a>
        <a href="index.php?page=main&controller=about&action=index">About</a>
        <a href="index.php?page=main&controller=products&action=index">Shop</a>
        <a href="index.php?page=main&controller=blog&action=index">Blog</a>
        <a href="index.php?page=main&controller=contact&action=index">Contact</a>
    </nav>
    <div class="mobile-nav-footer">
        <?php if (!isset($_SESSION['username'])): ?>
            <a href="index.php?page=main&controller=login&action=index"
               class="btn-black" style="justify-content:center;">Sign In</a>
        <?php else: ?>
            <a href="index.php?page=main&controller=account&action=index"
               class="mobile-account-link">My Account</a>
            <a href="index.php?page=main&controller=login&action=logout"
               class="mobile-account-link">Sign Out</a>
        <?php endif; ?>
    </div>
</div>

<!-- ╔══════════════════════════════╗
     ║   BREADCRUMB                ║
     ╚══════════════════════════════╝ -->
<?php if($current_controller !== 'products' && $current_controller !== 'home'): ?>
    <div class="breadcrumb-bar">
        <div class="container">
            <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                <a href="index.php"><i class="fa fa-home"></i></a>
                <span class="breadcrumb-sep">/</span>
                <span><?php echo ucfirst(htmlspecialchars($current_controller)); ?></span>
            </nav>
        </div>
    </div>
<?php endif; ?>

<!-- ╔══════════════════════════════╗
     ║   NAVBAR INLINE JS          ║
     ╚══════════════════════════════╝ -->
<script>
(function () {
    // Scroll shadow
    var hdr = document.getElementById('siteHeader');
    window.addEventListener('scroll', function () {
        hdr.classList.toggle('scrolled', window.scrollY > 30);
    }, { passive: true });

    // ── Search overlay ──
    var searchOverlay = document.getElementById('searchOverlay');
    var searchInput   = document.getElementById('searchInput');
    document.getElementById('searchToggle').addEventListener('click', function () {
        searchOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
        setTimeout(function () { searchInput && searchInput.focus(); }, 50);
    });
    document.getElementById('searchClose').addEventListener('click', closeSearch);
    searchOverlay.addEventListener('click', function (e) {
        if (e.target === searchOverlay) closeSearch();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeSearch(); closeCart(); closeMobileNav(); }
    });
    function closeSearch() {
        searchOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    // ── Cart panel ──
    var cartToggle  = document.getElementById('cartToggle');
    var cartPanel   = document.getElementById('cartPanel');
    var cartOverlay = document.getElementById('cartOverlay');
    var cartClose   = document.getElementById('cartClose');
    if (cartToggle) {
        cartToggle.addEventListener('click', function () {
            cartPanel.classList.add('open');
            cartOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
        cartClose.addEventListener('click', closeCart);
        cartOverlay.addEventListener('click', closeCart);
    }
    function closeCart() {
        if (!cartPanel) return;
        cartPanel.classList.remove('open');
        cartOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }
    window.closeCart = closeCart;

    // ── Mobile nav drawer ──
    var navToggle      = document.getElementById('navToggle');
    var mobileDrawer   = document.getElementById('mobileNavDrawer');
    var mobileOverlay  = document.getElementById('mobileNavOverlay');
    var mobileClose    = document.getElementById('mobileNavClose');
    navToggle.addEventListener('click', function () {
        mobileDrawer.classList.add('open');
        mobileOverlay.classList.add('open');
        navToggle.classList.add('is-open');
        navToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    });
    mobileClose.addEventListener('click', closeMobileNav);
    mobileOverlay.addEventListener('click', closeMobileNav);
    function closeMobileNav() {
        mobileDrawer.classList.remove('open');
        mobileOverlay.classList.remove('open');
        navToggle.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }
}());
</script>
