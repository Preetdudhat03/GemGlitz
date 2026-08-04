<?php
$page_title = "Contact Concierge & FAQ";
$page_desc = "Get in touch with GemGlitz private concierge team for bespoke consultations, order inquiries, or store visits.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$success_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO contact (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        $success_msg = "Thank you, {$name}. Your private message has been received. Our concierge will contact you within 2 hours.";
    }
}
?>

<section class="section-padding">
  <div class="container">
    
    <div class="text-center" style="margin-bottom:3.5rem;">
      <span class="section-subtitle">Client Concierge</span>
      <h1 class="section-title">Private Consultation & Support</h1>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:4rem;">
      
      <!-- Contact Form -->
      <div style="background:var(--white); padding:2.5rem; border-radius:var(--radius-lg); border:1px solid var(--border-color); box-shadow:var(--shadow-sm);">
        <h3 style="font-size:1.4rem; margin-bottom:1.5rem;">Send a Direct Message</h3>

        <?php if (!empty($success_msg)): ?>
          <div style="background:rgba(76,175,80,0.1); border:1px solid var(--success); color:var(--success); padding:1rem; border-radius:var(--radius-sm); font-size:0.9rem; margin-bottom:1.5rem;">
            <i class="fa-solid fa-circle-check"></i> <?php echo sanitize($success_msg); ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="contact.php">
          <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-control" placeholder="Keya Dudhat" required>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="form-group">
              <label class="form-label">Email Address *</label>
              <input type="email" name="email" class="form-control" placeholder="keyadudhat@gmail.com" required>
            </div>
            <div class="form-group">
              <label class="form-label">Phone Number</label>
              <input type="text" name="phone" class="form-control" placeholder="+1 212 555 0148">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Subject *</label>
            <input type="text" name="subject" class="form-control" placeholder="e.g. Bespoke Solitaire Consultation" required>
          </div>

          <div class="form-group">
            <label class="form-label">Message / Inquiry *</label>
            <textarea name="message" rows="5" class="form-control" placeholder="Please describe your requirements..." required></textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa-solid fa-paper-plane"></i> Send to Concierge</button>
        </form>
      </div>

      <!-- Atelier Contact Details & Map -->
      <div>
        <div style="margin-bottom:2.5rem;">
          <h3 style="font-size:1.4rem; margin-bottom:1rem;">Flagship Vault Atelier</h3>
          <p style="color:var(--text-secondary); font-size:0.95rem; margin-bottom:1.5rem; line-height:1.7;">
            Visit our private flagship salon at Shri Bhagubhai Mafatlal Polytechnic, Vile Parle (W), Mumbai, or schedule a virtual appointment with a master gemologist.
          </p>

          <div style="display:flex; flex-direction:column; gap:1.25rem;">
            <div style="display:flex; align-items:center; gap:1rem;">
              <div style="width:45px; height:45px; border-radius:50%; background:rgba(200,169,106,0.12); color:var(--primary-gold); display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-location-dot"></i>
              </div>
              <div>
                <strong style="display:block; font-size:0.9rem;">Atelier Address</strong>
                <span style="font-size:0.85rem; color:var(--text-secondary);">Shri Bhagubhai Mafatlal Polytechnic, Vile Parle (W), Mumbai, Maharashtra 400056, India</span>
              </div>
            </div>

            <div style="display:flex; align-items:center; gap:1rem;">
              <div style="width:45px; height:45px; border-radius:50%; background:rgba(200,169,106,0.12); color:var(--primary-gold); display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-phone"></i>
              </div>
              <div>
                <strong style="display:block; font-size:0.9rem;">VIP Hotline</strong>
                <span style="font-size:0.85rem; color:var(--text-secondary);">+91 98765 43210 / +1 (800) 555-0199</span>
              </div>
            </div>

            <div style="display:flex; align-items:center; gap:1rem;">
              <div style="width:45px; height:45px; border-radius:50%; background:rgba(200,169,106,0.12); color:var(--primary-gold); display:flex; align-items:center; justify-content:center;">
                <i class="fa-solid fa-envelope"></i>
              </div>
              <div>
                <strong style="display:block; font-size:0.9rem;">Concierge Email</strong>
                <span style="font-size:0.85rem; color:var(--text-secondary);">concierge@gemglitz.com</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Google Map Placeholder Graphic -->
        <div style="background:var(--bg-card); height:220px; border-radius:var(--radius-md); overflow:hidden; border:1px solid var(--border-color); display:flex; align-items:center; justify-content:center; color:var(--primary-gold); text-align:center;">
          <div>
            <i class="fa-solid fa-map-location-dot fa-2x" style="margin-bottom:0.5rem;"></i>
            <div style="font-weight:600; font-size:0.95rem; color:var(--text-primary);">Vile Parle Atelier Location Map</div>
            <span style="font-size:0.75rem; color:var(--text-muted);">Interactive GPS map loaded (Mumbai, India)</span>
          </div>
        </div>

      </div>

    </div>

    <!-- FAQ Accordion Section -->
    <div style="margin-top:6rem;" id="faq">
      <div class="text-center" style="margin-bottom:3rem;">
        <span class="section-subtitle">FAQ</span>
        <h2 class="section-title">Frequently Asked Questions</h2>
      </div>

      <div style="max-width:800px; margin:0 auto; display:flex; flex-direction:column; gap:1rem;">
        
        <div class="faq-item" style="background:var(--white); border-radius:var(--radius-sm); border:1px solid var(--border-color); padding:1.25rem 1.5rem; cursor:pointer;" onclick="toggleFaq(this)">
          <div style="display:flex; justify-content:space-between; align-items:center; font-weight:600;">
            <span>Are GemGlitz diamonds certified by independent laboratories?</span>
            <i class="fa-solid fa-chevron-down" style="color:var(--primary-gold);"></i>
          </div>
          <div class="faq-ans" style="display:none; margin-top:0.75rem; color:#666; font-size:0.9rem; line-height:1.6;">
            Yes. Every solitaire diamond above 0.50 carats comes with an official physical GIA (Gemological Institute of America) or HRD Antwerp certificate detailing cut, color, clarity, and carat weight.
          </div>
        </div>

        <div class="faq-item" style="background:var(--white); border-radius:var(--radius-sm); border:1px solid var(--border-color); padding:1.25rem 1.5rem; cursor:pointer;" onclick="toggleFaq(this)">
          <div style="display:flex; justify-content:space-between; align-items:center; font-weight:600;">
            <span>How are orders packaged and delivered?</span>
            <i class="fa-solid fa-chevron-down" style="color:var(--primary-gold);"></i>
          </div>
          <div class="faq-ans" style="display:none; margin-top:0.75rem; color:#666; font-size:0.9rem; line-height:1.6;">
            All creations are encased in handcrafted mahogany wooden jewelry boxes, wrapped in discrete unbranded outer boxes, and transported via fully insured armored courier services with mandatory signature upon delivery.
          </div>
        </div>

        <div class="faq-item" style="background:var(--white); border-radius:var(--radius-sm); border:1px solid var(--border-color); padding:1.25rem 1.5rem; cursor:pointer;" onclick="toggleFaq(this)">
          <div style="display:flex; justify-content:space-between; align-items:center; font-weight:600;">
            <span>What is your return policy?</span>
            <i class="fa-solid fa-chevron-down" style="color:var(--primary-gold);"></i>
          </div>
          <div class="faq-ans" style="display:none; margin-top:0.75rem; color:#666; font-size:0.9rem; line-height:1.6;">
            We offer a 30-day complimentary return policy on all unworn catalog items in their original condition with security tags intact.
          </div>
        </div>

      </div>
    </div>

  </div>
</section>

<script>
function toggleFaq(el) {
  const ans = el.querySelector('.faq-ans');
  const icon = el.querySelector('i');
  if (ans.style.display === 'block') {
    ans.style.display = 'none';
    icon.className = 'fa-solid fa-chevron-down';
  } else {
    ans.style.display = 'block';
    icon.className = 'fa-solid fa-chevron-up';
  }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
