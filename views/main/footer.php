<!-- ╔══════════════════════════════════════╗
     ║  SITE FOOTER                         ║
     ╚══════════════════════════════════════╝ -->
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-grid">

            <!-- Brand column -->
            <div class="footer-brand">
                <a href="index.php" class="site-logo">BKSHOP</a>
                <p>Premium fashion designed for those who believe style is a form of self-expression. Quality pieces, crafted to last.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                </div>
            </div>

            <!-- Information -->
            <div class="footer-col">
                <h6>Information</h6>
                <a href="index.php?page=main&controller=about&action=index">About Us</a>
                <a href="index.php?page=main&controller=contact&action=index">Contact</a>
                <a href="index.php?page=main&controller=blog&action=index">Blog</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>

            <!-- Account -->
            <div class="footer-col">
                <h6>Account</h6>
                <a href="index.php?page=main&controller=login&action=index">Sign In</a>
                <a href="index.php?page=main&controller=cart&action=index">Shopping Bag</a>
                <a href="index.php?page=main&controller=cart&action=payment">Checkout</a>
                <a href="#">Order Tracking</a>
                <a href="#">Size Guide</a>
            </div>

            <!-- Newsletter -->
            <div class="footer-newsletter">
                <h6>Stay in Touch</h6>
                <p>Get exclusive access to new arrivals, style tips, and subscriber-only offers.</p>
                <form class="newsletter-form" onsubmit="return false;">
                    <input type="email" class="newsletter-input" placeholder="Your email address" aria-label="Email address">
                    <button type="submit" class="newsletter-submit">Subscribe</button>
                </form>
                <p style="margin-top:.75rem;font-size:.7rem;color:#525252;letter-spacing:.04em;">
                    <i class="fas fa-lock" style="margin-right:4px;"></i>No spam. Unsubscribe anytime.
                </p>
            </div>

        </div><!-- /footer-grid -->

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> BKSHOP Co. All rights reserved.</p>
            <div class="footer-pay-icons">
                <i class="fab fa-cc-visa"       title="Visa"></i>
                <i class="fab fa-cc-mastercard" title="Mastercard"></i>
                <i class="fab fa-cc-paypal"     title="PayPal"></i>
            </div>
        </div>
    </div>
</footer>

<!-- ── Scripts ── -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.min.js"></script>
<script src="<?php echo $folderPath.'/script.js' ?>"></script>
<script src="public/js/animations.js"></script>

<!-- ── Cart rendering ── -->
<script>
var cartItems = <?php echo isset($data['cartItems']) ? json_encode($data['cartItems']) : '[]'; ?>;

function renderCart(items) {
    var list  = document.getElementById('product-list');
    var badge = document.getElementById('cartCount');
    var total = document.getElementById('total_price');
    if (!list) return;

    if (!items || items.length === 0) {
        list.innerHTML = '<div class="cart-empty-state">'
            + '<i class="fas fa-shopping-bag"></i>'
            + '<p>Your bag is empty.</p>'
            + '<a href="index.php?page=main&controller=products&action=index" '
            + '   onclick="closeCart()" class="btn-black" style="margin-top:1rem;padding:12px 28px;font-size:.7rem;">Shop Now</a>'
            + '</div>';
        if (badge) badge.style.display = 'none';
        if (total) total.textContent = '0 VNĐ';
        return;
    }

    var html = '';
    var grand = 0;
    items.forEach(function (item) {
        var lineTotal = item.price * item.quantity;
        grand += lineTotal;
        var name = item.productName.length > 28
            ? item.productName.slice(0, 28) + '…'
            : item.productName;
        html += '<div class="cart-item">'
            + '<img class="cart-item-img" src="' + item.image + '" alt="' + name + '">'
            + '<div class="cart-item-info">'
            +   '<div class="cart-item-name">' + name + '</div>'
            +   '<div class="cart-item-meta">Size: ' + item.size + ' &nbsp;|&nbsp; Qty: ' + item.quantity + '</div>'
            +   '<div class="cart-item-price">' + new Intl.NumberFormat('vi-VN').format(item.price) + ' VNĐ</div>'
            + '</div>'
            + '<button class="cart-item-remove deleteBtn" data-id="' + item.cartItemId + '" aria-label="Remove">'
            +   '<i class="fas fa-times"></i>'
            + '</button>'
            + '</div>';
    });

    list.innerHTML = html;

    if (badge) {
        var qty = items.reduce(function (s, i) { return s + i.quantity; }, 0);
        badge.textContent = qty > 9 ? '9+' : qty;
        badge.style.display = qty > 0 ? 'flex' : 'none';
    }
    if (total) total.textContent = new Intl.NumberFormat('vi-VN').format(grand) + ' VNĐ';
}

$(document).ready(function () {
    renderCart(cartItems);
});

$(document).on('click', '.deleteBtn', function (e) {
    e.preventDefault();
    var id  = $(this).data('id');
    var btn = this;
    $(btn).closest('.cart-item').style && ($(btn).closest('.cart-item')[0].style.opacity = '.4');
    $.ajax({
        url: 'index.php?page=main&controller=cart&action=deleteItem&id=' + id,
        type: 'GET',
        success: function (response) {
            try { response = JSON.parse(response); } catch(e) {}
            if (response.status === 200) {
                cartItems = cartItems.filter(function (i) { return i.cartItemId !== id; });
                renderCart(cartItems);
            } else {
                alert('Could not remove item. Please try again.');
                renderCart(cartItems);
            }
        },
        error: function () { renderCart(cartItems); }
    });
});
</script>

</body>
</html>
