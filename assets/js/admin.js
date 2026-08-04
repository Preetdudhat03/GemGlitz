/* ==========================================================================
   GemGlitz - Admin Panel JavaScript
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  initImagePreviews();
});

// Image Upload Live Preview in Product CRUD Modal
function initImagePreviews() {
  const fileInput = document.getElementById('product-image-input');
  const previewImg = document.getElementById('product-image-preview');
  if (!fileInput || !previewImg) return;

  fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        previewImg.src = event.target.result;
        previewImg.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  });
}
