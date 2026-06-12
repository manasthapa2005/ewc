document.addEventListener('DOMContentLoaded', () => {
    console.log("ONGC EWS Dashboard View Mode Initialized with 0 scroll strategy.");
    
    // --- HERO BACKGROUND CAROUSEL LOGIC ---
    const slides = document.querySelectorAll('.hero-slides .slide');
    let currentSlideIndex = 0;
    const slideIntervalTime = 4000; // Cycles every 4 seconds

    function cycleHeroSlides() {
        // 1. Remove active visibility class from current target slide
        slides[currentSlideIndex].classList.remove('active');
        
        // 2. FIXED: Uses the dynamic array length property (slides.length evaluates strictly to 4)
        // This ensures the formula calculates: (3 + 1) % 4 = 0, wrapping cleanly back to the first slide!
        currentSlideIndex = (currentSlideIndex + 1) % slides.length;
        
        // 3. Add class back to trigger opacity transition interpolation
        slides[currentSlideIndex].classList.add('active');
    }

    // Set loop execution cycle interval track
    if (slides.length > 1) {
        setInterval(cycleHeroSlides, slideIntervalTime);
    }

    // Prevent accidental overflow context adjustments on system resize
    window.addEventListener('resize', () => {
        document.body.style.height = `${window.innerHeight}px`;
    });

    // --- ADMIN LOGIN MODAL LOGIC ---
    const adminModal = document.getElementById('admin-login-modal');
    const closeBtn = document.getElementById('admin-modal-close-btn');
    const togglePasswordBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('admin-password');

    if (adminModal) {
        // Ensure modal is visible
        adminModal.classList.remove('hidden');

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                window.location.href = 'main.php?frmid=0';
            });
        }

        // Close on clicking backdrop overlay
        adminModal.addEventListener('click', (e) => {
            if (e.target === adminModal) {
                window.location.href = 'main.php?frmid=0';
            }
        });
    }

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            togglePasswordBtn.textContent = isPassword ? '🔒' : '👁️';
        });
    }
});