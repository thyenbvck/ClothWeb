<?php require_once('views/main/navbar.php'); ?>

<!-- ╔══════════════════════════════════════╗
     ║  HERO — Full-width carousel          ║
     ╚══════════════════════════════════════╝ -->
<section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0"
                    class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active hero-slide" style="background-image:url('public/images/products/slide1.png')">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <span class="hero-eyebrow">New Collection 2025</span>
                    <h1 class="hero-title">Redefine<br><em>Your Style</em></h1>
                    <p class="hero-sub">Premium fashion crafted for those who appreciate quality and design.</p>
                    <div class="hero-actions">
                        <a href="index.php?page=main&controller=products&action=index" class="btn-hero-primary">Shop Now</a>
                        <a href="index.php?page=main&controller=about&action=index" class="btn-hero-ghost">Our Story</a>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item hero-slide" style="background-image:url('https://i.pinimg.com/564x/54/a5/b2/54a5b213c3887a3459fe22b83a474e8c.jpg')">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <span class="hero-eyebrow">Limited Edition</span>
                    <h1 class="hero-title">Timeless<br><em>Elegance</em></h1>
                    <p class="hero-sub">Discover pieces that transcend seasons and trends.</p>
                    <div class="hero-actions">
                        <a href="index.php?page=main&controller=products&action=index" class="btn-hero-primary">Explore</a>
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="carousel-item hero-slide" style="background-image:url('https://i.pinimg.com/564x/09/bc/a9/09bca94d568f6985f23c1586eb3c33b4.jpg')">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <span class="hero-eyebrow">Exclusive Drops</span>
                    <h1 class="hero-title">Bold.<br><em>Modern. You.</em></h1>
                    <p class="hero-sub">Express yourself with our curated seasonal collections.</p>
                    <div class="hero-actions">
                        <a href="index.php?page=main&controller=products&action=index" class="btn-hero-primary">View Collection</a>
                    </div>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <i class="fas fa-chevron-left hero-arrow"></i>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <i class="fas fa-chevron-right hero-arrow"></i>
        </button>
    </div>
</section>

<!-- ╔══════════════════════════════════════╗
     ║  USP STRIP                           ║
     ╚══════════════════════════════════════╝ -->
<section class="usp-strip">
    <div class="usp-grid">
        <div class="usp-item reveal">
            <i class="fas fa-truck"></i>
            <strong>Free Shipping</strong>
            <span>On orders over 500K VND</span>
        </div>
        <div class="usp-item reveal">
            <i class="fas fa-undo-alt"></i>
            <strong>Easy Returns</strong>
            <span>30-day hassle-free returns</span>
        </div>
        <div class="usp-item reveal">
            <i class="fas fa-shield-alt"></i>
            <strong>Secure Payment</strong>
            <span>100% protected transactions</span>
        </div>
        <div class="usp-item reveal">
            <i class="fas fa-star"></i>
            <strong>Premium Quality</strong>
            <span>Carefully selected materials</span>
        </div>
    </div>
</section>

<!-- ╔══════════════════════════════════════╗
     ║  CATEGORY GRID                       ║
     ╚══════════════════════════════════════╝ -->
<section class="home-section">
    <div class="container-fluid px-4 px-md-5">
        <div class="sec-head reveal">
            <span class="eyebrow">Browse by</span>
            <h2>Shop Categories</h2>
        </div>
        <div class="category-grid">
            <a href="index.php?page=main&controller=products&action=index" class="category-card reveal"
               style="background-image:url('https://i.pinimg.com/564x/54/a5/b2/54a5b213c3887a3459fe22b83a474e8c.jpg')">
                <div class="cat-overlay"></div>
                <div class="cat-label">
                    <span>Jackets</span>
                    <small>Explore →</small>
                </div>
            </a>
            <a href="index.php?page=main&controller=products&action=index" class="category-card reveal"
               style="background-image:url('https://i.pinimg.com/564x/09/bc/a9/09bca94d568f6985f23c1586eb3c33b4.jpg')">
                <div class="cat-overlay"></div>
                <div class="cat-label">
                    <span>T-Shirts</span>
                    <small>Explore →</small>
                </div>
            </a>
            <a href="index.php?page=main&controller=products&action=index" class="category-card reveal"
               style="background-image:url('https://i.pinimg.com/564x/54/a5/b2/54a5b213c3887a3459fe22b83a474e8c.jpg')">
                <div class="cat-overlay"></div>
                <div class="cat-label">
                    <span>Hoodies</span>
                    <small>Explore →</small>
                </div>
            </a>
            <a href="index.php?page=main&controller=products&action=index" class="category-card reveal"
               style="background-image:url('https://i.pinimg.com/564x/09/bc/a9/09bca94d568f6985f23c1586eb3c33b4.jpg')">
                <div class="cat-overlay"></div>
                <div class="cat-label">
                    <span>Footwear</span>
                    <small>Explore →</small>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ╔══════════════════════════════════════╗
     ║  NEW ARRIVALS — Product grid         ║
     ╚══════════════════════════════════════╝ -->
<section class="home-section">
    <div class="container-fluid px-4 px-md-5">
        <div class="sec-head reveal">
            <span class="eyebrow">Just Dropped</span>
            <h2>New Arrivals</h2>
            <p>Fresh styles added every week. Be the first to wear them.</p>
        </div>

        <div class="product-grid">
            <?php $count = 0; foreach($products as $product): if($count >= 8) break; $count++; ?>
            <div class="product-card reveal">
                <!-- Image -->
                <div class="product-card-media">
                    <span class="product-badge"><span class="badge-pill badge-new">New</span></span>
                    <button class="wishlist-btn" aria-label="Add to wishlist">
                        <i class="far fa-heart"></i>
                    </button>
                    <img src="<?php echo htmlspecialchars($product->getImageUrl()); ?>"
                         alt="<?php echo htmlspecialchars($product->getProductName()); ?>"
                         loading="lazy">
                    <a href="index.php?page=main&controller=products&action=viewdetail&id=<?php echo $product->getProductId(); ?>"
                       class="quick-add-bar">Quick View</a>
                </div>
                <!-- Info -->
                <div class="product-card-info">
                    <p class="product-card-cat">BKSHOP</p>
                    <a href="index.php?page=main&controller=products&action=viewdetail&id=<?php echo $product->getProductId(); ?>">
                        <p class="product-card-name"><?php echo htmlspecialchars($product->getProductName()); ?></p>
                    </a>
                    <div class="product-card-price">
                        <span class="p-current"><?php echo number_format($product->getPrice(), 0, ',', '.'); ?> VND</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="home-cta-wrap reveal">
            <a href="index.php?page=main&controller=products&action=index" class="btn-outline">
                View All Products &nbsp;<i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- ╔══════════════════════════════════════╗
     ║  EDITORIAL BANNER                    ║
     ╚══════════════════════════════════════╝ -->
<section class="editorial-banner reveal"
         style="background-image:url('public/images/products/slide1.png')">
    <div class="editorial-overlay"></div>
    <div class="editorial-content">
        <span class="hero-eyebrow">The Season's Must-Haves</span>
        <h2 class="editorial-title">Style is a way<br>to say who you are.</h2>
        <a href="index.php?page=main&controller=products&action=index"
           class="btn-hero-primary">Explore Collection</a>
    </div>
</section>

<!-- ╔══════════════════════════════════════╗
     ║  BLOG PREVIEW                        ║
     ╚══════════════════════════════════════╝ -->
<section class="home-section">
    <div class="container-fluid px-4 px-md-5">
        <div class="sec-head reveal">
            <span class="eyebrow">Inspiration</span>
            <h2>From the Journal</h2>
        </div>
        <div class="blog-preview-grid">
            <a href="index.php?page=main&controller=blog&action=index" class="blog-preview-card reveal">
                <div class="blog-preview-img" style="background-image:url('https://i.pinimg.com/564x/54/a5/b2/54a5b213c3887a3459fe22b83a474e8c.jpg')"></div>
                <div class="blog-preview-info">
                    <span class="blog-tag">Style Guide</span>
                    <h4>How to Build the Perfect Capsule Wardrobe</h4>
                    <p>Discover the art of minimalist dressing with pieces that work year-round.</p>
                    <span class="blog-read-more">Read More →</span>
                </div>
            </a>
            <a href="index.php?page=main&controller=blog&action=index" class="blog-preview-card reveal">
                <div class="blog-preview-img" style="background-image:url('https://i.pinimg.com/564x/09/bc/a9/09bca94d568f6985f23c1586eb3c33b4.jpg')"></div>
                <div class="blog-preview-info">
                    <span class="blog-tag">Trends</span>
                    <h4>Summer 2025: The Colours That Define the Season</h4>
                    <p>From warm earth tones to electric blues — what to wear this summer.</p>
                    <span class="blog-read-more">Read More →</span>
                </div>
            </a>
            <a href="index.php?page=main&controller=blog&action=index" class="blog-preview-card reveal">
                <div class="blog-preview-img" style="background-image:url('https://i.pinimg.com/564x/54/a5/b2/54a5b213c3887a3459fe22b83a474e8c.jpg')"></div>
                <div class="blog-preview-info">
                    <span class="blog-tag">Sustainability</span>
                    <h4>Why Slow Fashion Is the Future of Style</h4>
                    <p>Quality over quantity — investing in fewer, better pieces.</p>
                    <span class="blog-read-more">Read More →</span>
                </div>
            </a>
        </div>
    </div>
</section>

<?php require_once('views/main/footer.php'); ?>
