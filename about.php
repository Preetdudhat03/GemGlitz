<?php
$page_title = "Our Heritage & Craftsmanship";
$page_desc = "Learn about GemGlitz centuries-old lineage of master jewelers, ethical diamond sourcing, and haute joaillerie craftsmanship.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- About Hero -->
<section style="background: linear-gradient(135deg, #111, #1C1917); color:#FFF; padding: 5rem 0; text-align:center;">
  <div class="container" style="max-width:800px;">
    <span class="section-subtitle">Since 1894</span>
    <h1 style="font-size:3.5rem; color:#FFF; margin-bottom:1rem;">Haute Joaillerie Redefined</h1>
    <p style="color:#CCC; font-size:1.1rem; line-height:1.8;">
      GemGlitz is synonymous with extreme gemological rarity, architectural gold design, and uncompromising precision.
    </p>
  </div>
</section>

<!-- Our Story Section -->
<section class="section-padding">
  <div class="container" style="display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:center;">
    <div>
      <span class="section-subtitle">The Legacy</span>
      <h2 class="section-title">Born in Geneva & New York</h2>
      <p style="color:#666; margin-bottom:1.25rem; line-height:1.8;">
        Founded in 1894 by master jeweler Henri Vanderbilt, GemGlitz began as a private appointment atelier serving European royalty and discerning global art collectors.
      </p>
      <p style="color:#666; margin-bottom:1.5rem; line-height:1.8;">
        Today, our master craftsmen combine hand-drawn Parisian gouache sketches with state-of-the-art laser microscopy to hand-set D-Flawless solitaires, Colombian emeralds, and Ceylon sapphires into solid 18K yellow gold and platinum.
      </p>
      <div style="border-left:3px solid var(--primary-gold); padding-left:1.5rem; font-style:italic; color:var(--dark-bg); font-family:var(--font-heading); font-size:1.2rem;">
        "We do not merely craft jewelry; we forge heirloom monuments that outlast time itself."
      </div>
    </div>
    <div style="background:var(--dark-card); padding:2rem; border-radius:var(--radius-lg); border:1px solid var(--border-color);">
      <img src="assets/images/product_ring_1.svg" alt="GemGlitz Heritage" style="border-radius:var(--radius-md);">
    </div>
  </div>
</section>

<!-- Values Grid -->
<section class="section-padding" style="background:var(--secondary-bg);">
  <div class="container">
    <div class="text-center" style="margin-bottom: 3.5rem;">
      <span class="section-subtitle">Our Pillars</span>
      <h2 class="section-title">The GemGlitz Commitment</h2>
    </div>

    <div class="grid-3">
      <div style="background:var(--white); padding:2.5rem; border-radius:var(--radius-md); border:1px solid var(--border-color); text-align:center;">
        <i class="fa-solid fa-award fa-2x" style="color:var(--primary-gold); margin-bottom:1.5rem;"></i>
        <h3 style="font-size:1.3rem; margin-bottom:1rem;">GIA Certification</h3>
        <p style="color:#666; font-size:0.9rem;">Every solitaire above 0.50 carats comes accompanied by an official GIA or HRD International grading report.</p>
      </div>

      <div style="background:var(--white); padding:2.5rem; border-radius:var(--radius-md); border:1px solid var(--border-color); text-align:center;">
        <i class="fa-solid fa-hand-holding-heart fa-2x" style="color:var(--primary-gold); margin-bottom:1.5rem;"></i>
        <h3 style="font-size:1.3rem; margin-bottom:1rem;">100% Conflict-Free</h3>
        <p style="color:#666; font-size:0.9rem;">We strictly adhere to the Kimberley Process, utilizing only ethically mined diamonds and 100% recycled 18K gold.</p>
      </div>

      <div style="background:var(--white); padding:2.5rem; border-radius:var(--radius-md); border:1px solid var(--border-color); text-align:center;">
        <i class="fa-solid fa-shield-halved fa-2x" style="color:var(--primary-gold); margin-bottom:1.5rem;"></i>
        <h3 style="font-size:1.3rem; margin-bottom:1rem;">Armored Transport</h3>
        <p style="color:#666; font-size:0.9rem;">All acquisitions are shipped via fully insured, white-glove armored transport with discrete packaging.</p>
      </div>
    </div>
  </div>
</section>

<!-- Jewelry Care Guide Section -->
<section class="section-padding" id="care">
  <div class="container" style="max-width:800px;">
    <div class="text-center" style="margin-bottom:3rem;">
      <span class="section-subtitle">Maintenance Guide</span>
      <h2 class="section-title">Preserving Your Masterpiece</h2>
    </div>

    <div style="display:flex; flex-direction:column; gap:1.5rem;">
      <div style="background:var(--white); padding:1.5rem 2rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
        <h4 style="font-size:1.1rem; color:var(--primary-gold); margin-bottom:0.5rem;"><i class="fa-solid fa-gem"></i> Diamond & Gemstone Care</h4>
        <p style="color:#666; font-size:0.9rem;">Soak your diamond pieces in warm water mixed with mild dish soap for 20 minutes, then gently brush with a soft toothbrush.</p>
      </div>

      <div style="background:var(--white); padding:1.5rem 2rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
        <h4 style="font-size:1.1rem; color:var(--primary-gold); margin-bottom:0.5rem;"><i class="fa-solid fa-coins"></i> 18K Gold & Platinum Care</h4>
        <p style="color:#666; font-size:0.9rem;">Avoid exposing precious metals to harsh household chemicals, swimming pool chlorine, or heavy perfumes.</p>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
