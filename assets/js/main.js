/* ==========================================================================
   GemGlitz - Main JavaScript Application Logic
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  initDarkMode();
  initStickyNavbar();
  initLiveSearch();
  initBackToTop();
  initProductGallery();
});

// Toast Notification Helper
function showToast(message, type = 'success') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast ${type === 'error' ? 'toast-error' : ''}`;
  toast.innerHTML = `<i class="fa-solid ${type === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'}"></i> <span>${message}</span>`;

  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    setTimeout(() => toast.remove(), 400);
  }, 4000);
}

// Dark Mode Toggle & Persistence
function initDarkMode() {
  const toggleBtn = document.getElementById('dark-mode-toggle');
  if (!toggleBtn) return;

  const currentTheme = localStorage.getItem('gemglitz_theme');
  if (currentTheme === 'dark') {
    document.body.classList.add('dark-mode');
    toggleBtn.innerHTML = '<i class="fa-solid fa-sun"></i>';
  }

  toggleBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('gemglitz_theme', isDark ? 'dark' : 'light');
    toggleBtn.innerHTML = isDark ? '<i class="fa-solid fa-sun"></i>' : '<i class="fa-solid fa-moon"></i>';
    showToast(isDark ? 'Switched to Dark Mode' : 'Switched to Light Mode', 'info');
  });
}

// Sticky Navbar Scroll Listener
function initStickyNavbar() {
  const navbar = document.querySelector('.navbar-sticky');
  if (!navbar) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
    } else {
      navbar.style.boxShadow = 'none';
    }
  });
}

// Live Search Dropdown
function initLiveSearch() {
  const searchInput = document.getElementById('main-search-input');
  const searchResults = document.getElementById('search-results-dropdown');
  if (!searchInput || !searchResults) return;

  let debounceTimer;
  searchInput.addEventListener('input', (e) => {
    clearTimeout(debounceTimer);
    const query = e.target.value.trim();

    if (query.length < 2) {
      searchResults.style.display = 'none';
      return;
    }

    debounceTimer = setTimeout(() => {
      fetch(`api/live_search.php?q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success' && data.products.length > 0) {
            let html = '';
            data.products.forEach(p => {
              html += `
                <a href="product.php?id=${p.id}" class="search-result-item" style="display:flex; align-items:center; gap:1rem; padding:0.8rem 1rem; border-bottom:1px solid #eee;">
                  <img src="assets/images/${p.main_image}" style="width:40px; height:40px; object-fit:contain; border-radius:4px;">
                  <div>
                    <div style="font-weight:600; font-size:0.9rem;">${p.name}</div>
                    <div style="color:#C8A96A; font-size:0.85rem;">$${parseFloat(p.price).toFixed(2)}</div>
                  </div>
                </a>
              `;
            });
            searchResults.innerHTML = html;
            searchResults.style.display = 'block';
          } else {
            searchResults.innerHTML = '<div style="padding:1rem; text-align:center; color:#888;">No products found</div>';
            searchResults.style.display = 'block';
          }
        })
        .catch(() => {
          searchResults.style.display = 'none';
        });
    }, 300);
  });

  document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
      searchResults.style.display = 'none';
    }
  });
}

// Add To Cart via AJAX
function addToCart(productId, quantity = 1) {
  fetch('api/cart_action.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=add&product_id=${productId}&quantity=${quantity}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      showToast(data.message);
      const cartBadge = document.getElementById('cart-badge-count');
      if (cartBadge) cartBadge.innerText = data.cart_count;
    } else {
      showToast(data.message, 'error');
    }
  })
  .catch(() => showToast('Failed to update cart', 'error'));
}

// Toggle Wishlist via AJAX
function toggleWishlist(productId, btnElement = null) {
  fetch('api/wishlist_action.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=toggle&product_id=${productId}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      showToast(data.message);
      const wishlistBadge = document.getElementById('wishlist-badge-count');
      if (wishlistBadge) wishlistBadge.innerText = data.wishlist_count;
      if (btnElement) {
        if (data.in_wishlist) {
          btnElement.style.color = '#E53935';
        } else {
          btnElement.style.color = 'inherit';
        }
      }
    } else {
      showToast(data.message, 'error');
    }
  })
  .catch(() => showToast('Failed to update wishlist', 'error'));
}

// Quick View Modal Renderer
function openQuickView(productId) {
  const modal = document.getElementById('quick-view-modal');
  const container = document.getElementById('quick-view-content');
  if (!modal || !container) return;

  container.innerHTML = '<div style="text-align:center; padding:3rem;"><i class="fa-solid fa-spinner fa-spin fa-2x" style="color:#C8A96A;"></i></div>';
  modal.classList.add('active');

  fetch(`api/quick_view.php?id=${productId}`)
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        const p = data.product;
        container.innerHTML = `
          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:2.5rem;">
            <div style="background:#FAF7F2; border-radius:12px; padding:2rem; text-align:center;">
              <img src="assets/images/${p.main_image}" style="max-height:300px; margin:0 auto; object-fit:contain;">
            </div>
            <div>
              <span style="color:#C8A96A; font-size:0.8rem; font-weight:600; text-transform:uppercase;">${p.category_name}</span>
              <h2 style="font-family:'Playfair Display', serif; font-size:1.8rem; margin:0.5rem 0;">${p.name}</h2>
              <div style="color:#D4AF37; margin-bottom:1rem;"><i class="fa-solid fa-star"></i> ${p.rating} (${p.review_count} reviews)</div>
              <div style="font-size:1.5rem; font-weight:700; color:#C8A96A; margin-bottom:1rem;">$${parseFloat(p.price).toFixed(2)}</div>
              <p style="color:#666; font-size:0.9rem; margin-bottom:1.5rem;">${p.short_description || p.description}</p>
              <div style="display:flex; gap:1rem; margin-bottom:1.5rem;">
                <button onclick="addToCart(${p.id}); closeQuickView();" class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> Add to Cart</button>
                <button onclick="toggleWishlist(${p.id});" class="btn btn-outline"><i class="fa-regular fa-heart"></i> Wishlist</button>
              </div>
            </div>
          </div>
        `;
      } else {
        container.innerHTML = '<div style="color:#E53935; text-align:center;">Product not found.</div>';
      }
    });
}

function closeQuickView() {
  const modal = document.getElementById('quick-view-modal');
  if (modal) modal.classList.remove('active');
}

// Product Details Gallery Thumbnails
function initProductGallery() {
  const mainImg = document.getElementById('main-product-image');
  const thumbs = document.querySelectorAll('.gallery-thumb-item');
  if (!mainImg || !thumbs.length) return;

  thumbs.forEach(thumb => {
    thumb.addEventListener('click', () => {
      thumbs.forEach(t => t.style.borderColor = 'var(--border-color)');
      thumb.style.borderColor = 'var(--primary-gold)';
      mainImg.src = thumb.dataset.src;
    });
  });
}

// Back to Top Button
function initBackToTop() {
  const btn = document.getElementById('back-to-top');
  if (!btn) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 400) {
      btn.classList.add('show');
    } else {
      btn.classList.remove('show');
    }
  });

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}
