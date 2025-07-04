<div class="bottom-nav">
    <a href="{{ route('home') }}" class="nav-item {{ $active == 'home' ? 'active' : '' }}" title="Beranda">
        <span class="nav-icon">🏠</span>
        <span class="nav-label">Beranda</span>
    </a>
    <a href="{{ route('cart.index') }}" class="nav-item {{ $active == 'cart' ? 'active' : '' }}" title="Keranjang" id="cartLink">
        <span class="nav-icon">🛒</span>
        <span class="nav-label">Keranjang</span>
        <span class="cart-badge" id="cartBadge" style="display: none;">0</span>
    </a>
    <a href="#" class="nav-item" title="Favorit" onclick="showFavorites()">
        <span class="nav-icon">❤️</span>
        <span class="nav-label">Favorit</span>
    </a>
    <a href="#" class="nav-item" title="Profil" onclick="showProfile()">
        <span class="nav-icon">👤</span>
        <span class="nav-label">Profil</span>
    </a>
</div>

<script>
// Update cart badge on page load
document.addEventListener('DOMContentLoaded', function() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const badge = document.getElementById('cartBadge');
    
    if (badge) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        
        if (totalItems > 0) {
            badge.textContent = totalItems;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
});

function showFavorites() {
    showToast('Fitur favorit akan segera hadir!');
}

function showProfile() {
    showToast('Fitur profil akan segera hadir!');
}
</script>
