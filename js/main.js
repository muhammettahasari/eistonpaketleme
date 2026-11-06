/* ==========================================
   EISTON PAKETLEME - MAIN JAVASCRIPT
   ========================================== */

document.addEventListener('DOMContentLoaded', function () {

    // ---------- Preloader ----------
    const preloader = document.getElementById('preloader');
    window.addEventListener('load', function () {
        setTimeout(() => {
            preloader.classList.add('hidden');
            document.body.style.overflow = '';
        }, 800);
    });

    // Fallback - hide preloader after 3 seconds
    setTimeout(() => {
        if (preloader && !preloader.classList.contains('hidden')) {
            preloader.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }, 3000);

    // ---------- Navbar Scroll ----------
    const header = document.getElementById('header');
    const backToTop = document.getElementById('backToTop');

    function handleScroll() {
        const scrollY = window.scrollY;

        // Header
        if (scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        // Back to top
        if (backToTop) {
            if (scrollY > 400) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        }

        // Active nav link
        updateActiveNavLink();
    }

    window.addEventListener('scroll', handleScroll);
    handleScroll();

    // Back to top click
    if (backToTop) {
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ---------- Mobile Menu ----------
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', function () {
            this.classList.toggle('active');
            navLinks.classList.toggle('active');
            document.body.style.overflow = navLinks.classList.contains('active') ? 'hidden' : '';
        });

        // Close on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function () {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }

    // ---------- Active Nav Link ----------
    function updateActiveNavLink() {
        const sections = document.querySelectorAll('section[id]');
        const navItems = document.querySelectorAll('.nav-links a');
        let currentSection = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop - 120;
            if (window.scrollY >= sectionTop) {
                currentSection = section.getAttribute('id');
            }
        });

        navItems.forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href');
            if (href && href.includes('#') && href.includes(currentSection)) {
                link.classList.add('active');
            }
        });
    }

    // ---------- Smooth Scroll ----------
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // ---------- Scroll Animations ----------
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-fade-up, .animate-fade-right, .animate-fade-left').forEach(el => {
        observer.observe(el);
    });

    // ---------- Counter Animation ----------
    function animateCounter(el) {
        const target = parseInt(el.getAttribute('data-count'));
        const duration = 2000;
        const start = 0;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(start + (target - start) * easeOut);

            el.textContent = current;

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }

    const counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-number[data-count]').forEach(el => {
        counterObserver.observe(el);
    });

    // ---------- Contact Form ----------
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Simple validation
            if (!data.name || !data.email || !data.message) {
                showNotification('Lütfen zorunlu alanları doldurun.', 'error');
                return;
            }

            // Show success (replace with actual form submission)
            showNotification('Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.', 'success');
            this.reset();
        });
    }

    // ---------- Notification ----------
    function showNotification(message, type = 'success') {
        // Remove existing notification
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();

        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        // Styles
        Object.assign(notification.style, {
            position: 'fixed',
            top: '100px',
            right: '24px',
            padding: '16px 24px',
            borderRadius: '12px',
            background: type === 'success' ? '#10B981' : '#EF4444',
            color: '#fff',
            fontSize: '14px',
            fontWeight: '500',
            zIndex: '9999',
            boxShadow: '0 8px 32px rgba(0,0,0,0.2)',
            transform: 'translateX(120%)',
            transition: 'transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
            maxWidth: '400px'
        });

        notification.querySelector('.notification-content').style.cssText = 
            'display:flex;align-items:center;gap:10px;';

        document.body.appendChild(notification);

        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
        });

        setTimeout(() => {
            notification.style.transform = 'translateX(120%)';
            setTimeout(() => notification.remove(), 400);
        }, 4000);
    }

    // ---------- Product Filter (Products Page) ----------
    const filterTabs = document.querySelectorAll('.filter-tab');
    const productCategories = document.querySelectorAll('.product-category');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function () {
            const filter = this.getAttribute('data-filter');

            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Filter categories
            productCategories.forEach(category => {
                if (filter === 'all' || category.getAttribute('data-category') === filter) {
                    category.style.display = '';
                    // Re-trigger animations
                    category.querySelectorAll('.animate-fade-up').forEach(el => {
                        el.classList.remove('visible');
                        setTimeout(() => observer.observe(el), 50);
                    });
                } else {
                    category.style.display = 'none';
                }
            });

            // Smooth scroll to products
            const firstVisible = document.querySelector('.product-category:not([style*="display: none"])');
            if (firstVisible && filter !== 'all') {
                firstVisible.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ---------- Check Hash on Load (Products Page) ----------
    if (window.location.hash) {
        const hash = window.location.hash.substring(1);
        const targetTab = document.querySelector(`.filter-tab[data-filter="${hash}"]`);
        if (targetTab) {
            targetTab.click();
            setTimeout(() => {
                const targetSection = document.querySelector(`[data-category="${hash}"]`);
                if (targetSection) {
                    targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        }
    }

});
