<!-- Footer Section -->
<footer>
  <div class="container">
    <div class="footer-grid">
      
      <!-- Brand Summary -->
      <div class="footer-col">
        <a href="index.php" class="brand-logo" style="margin-bottom:1rem; color:var(--white);">
          <img src="assets/images/logo.svg" alt="GemGlitz Logo" style="height:35px;">
          <span>GEMGLITZ</span>
        </a>
        <p style="color:#999; font-size:0.9rem; margin-bottom:1.5rem; line-height:1.7;">
          GemGlitz represents the pinnacle of haute joaillerie. Each masterpiece is individually hand-set by master artisans in Geneva and New York using GIA-certified conflict-free diamonds and ethically sourced gold.
        </p>
        <div style="display:flex; gap:1rem; color:var(--primary-gold); font-size:1.2rem;">
          <a href="#" style="color:inherit;"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" style="color:inherit;"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" style="color:inherit;"><i class="fa-brands fa-pinterest"></i></a>
          <a href="#" style="color:inherit;"><i class="fa-brands fa-twitter"></i></a>
        </div>
      </div>

      <!-- Quick Navigation Links -->
      <div class="footer-col">
        <h4>Explore Collections</h4>
        <ul class="footer-links">
          <li><a href="shop.php?category=diamond-rings">Solitaire Rings</a></li>
          <li><a href="shop.php?category=luxury-necklaces">Royal Necklaces</a></li>
          <li><a href="shop.php?category=royal-bracelets">Tennis Bracelets</a></li>
          <li><a href="shop.php?category=elegant-earrings">Sapphire Earrings</a></li>
          <li><a href="shop.php?category=luxury-watches">Swiss Horology</a></li>
        </ul>
      </div>

      <!-- Customer Care -->
      <div class="footer-col">
        <h4>Customer Concierge</h4>
        <ul class="footer-links">
          <li><a href="order_tracking.php">Track Order</a></li>
          <li><a href="about.php">Our Heritage</a></li>
          <li><a href="contact.php">Contact Concierge</a></li>
          <li><a href="contact.php#faq">Frequently Asked Questions</a></li>
          <li><a href="about.php#care">Jewelry Care Guide</a></li>
        </ul>
      </div>

      <!-- Newsletter -->
      <div class="footer-col">
        <h4>Private VIP Circle</h4>
        <p style="color:#999; font-size:0.85rem; margin-bottom:1rem;">
          Subscribe to receive exclusive invitations to private vault releases and bespoke jewelry consultations.
        </p>
        <form onsubmit="event.preventDefault(); showToast('Thank you for subscribing to GemGlitz Private Circle!'); this.reset();" style="display:flex; gap:0.5rem;">
          <input type="email" placeholder="Enter your email" required style="padding:0.75rem 1rem; border-radius:50px; border:1px solid #333; background:#1A1A1A; color:#FFF; font-size:0.85rem; flex-grow:1; outline:none;">
          <button type="submit" class="btn btn-primary" style="padding:0.75rem 1.25rem;"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
      </div>

    </div>

    <!-- Copyright Bar -->
    <div class="copyright-bar">
      <div>&copy; <?php echo date('Y'); ?> GemGlitz Haute Joaillerie Ltd. All Rights Reserved.</div>
      <div style="display:flex; gap:1rem; font-size:1.4rem; color:#666;">
        <i class="fa-brands fa-cc-visa"></i>
        <i class="fa-brands fa-cc-mastercard"></i>
        <i class="fa-brands fa-cc-amex"></i>
        <i class="fa-brands fa-cc-paypal"></i>
        <i class="fa-brands fa-cc-apple-pay"></i>
      </div>
    </div>

  </div>
</footer>

<!-- Floating Widgets -->
<div class="floating-btn-container">
  <!-- Floating WhatsApp Contact -->
  <a href="https://wa.me/18005550199" target="_blank" class="floating-whatsapp" title="Chat with Luxury Concierge">
    <i class="fa-brands fa-whatsapp"></i>
  </a>
  <!-- Back to Top Button -->
  <div id="back-to-top" class="back-to-top" title="Scroll to Top">
    <i class="fa-solid fa-arrow-up"></i>
  </div>
</div>

<!-- Main Script Include -->
<script src="assets/js/main.js"></script>
</body>
</html>
