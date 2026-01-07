console.log("Ruhban Abdullah");

/* ==========================================
   COMMON JAVASCRIPT FOR ALL PAGES
   Developer Ruhban Portfolio
   ========================================== */

// ==========================================
// MOBILE MENU TOGGLE
// ==========================================
const menuToggle = document.getElementById('menuToggle');
const nav = document.getElementById('nav');
const overlay = document.getElementById('overlay');

if (menuToggle && nav && overlay) {
    menuToggle.addEventListener('click', () => {
        menuToggle.classList.toggle('active');
        nav.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
        menuToggle.classList.remove('active');
        nav.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Close menu on link click
    const navLinks = nav.querySelectorAll('a');
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            // Don't close if it's a dropdown toggle on mobile
            if (link.classList.contains('dropdown-toggle') && window.innerWidth <= 768) {
                e.preventDefault();
                const dropdown = link.closest('.dropdown');
                dropdown.classList.toggle('active');
                return;
            }

            menuToggle.classList.remove('active');
            nav.classList.remove('active');
            overlay.classList.remove('active');
        });
    });
}

// ==========================================
// SCROLL TO TOP BUTTON
// ==========================================
const scrollTop = document.getElementById('scrollTop');

if (scrollTop) {
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollTop.classList.add('visible');
        } else {
            scrollTop.classList.remove('visible');
        }
    });

    scrollTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// ==========================================
// SMOOTH SCROLL FOR ANCHOR LINKS
// ==========================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');

        // Skip if it's just "#" or if it's a dropdown toggle
        if (href === '#' || this.classList.contains('dropdown-toggle')) {
            return;
        }

        e.preventDefault();
        const target = document.querySelector(href);

        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ==========================================
// ACTIVE NAV LINK ON SCROLL
// ==========================================
const sections = document.querySelectorAll('section[id]');
const navLinksAll = document.querySelectorAll('nav a');

if (sections.length > 0 && navLinksAll.length > 0) {
    window.addEventListener('scroll', () => {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= (sectionTop - 200)) {
                current = section.getAttribute('id');
            }
        });

        navLinksAll.forEach(link => {
            link.style.color = '';
            link.style.borderBottomColor = '';
            link.style.borderLeftColor = '';

            if (link.getAttribute('href') === `#${current}`) {
                link.style.color = 'var(--accent-purple)';
                if (window.innerWidth > 768) {
                    link.style.borderBottomColor = 'var(--accent-purple)';
                } else {
                    link.style.borderLeftColor = 'var(--accent-purple)';
                }
            }
        });
    });
}

// ==========================================
// ACCORDION FUNCTIONALITY (FAQ)
// ==========================================
const accordionHeaders = document.querySelectorAll('.accordion-header');

if (accordionHeaders.length > 0) {
    accordionHeaders.forEach(button => {
        button.addEventListener('click', () => {
            const accordion = button.parentElement;

            // Close other accordions
            document.querySelectorAll('.accordion').forEach(item => {
                if (item !== accordion) {
                    item.classList.remove('active');
                }
            });

            // Toggle current accordion
            accordion.classList.toggle('active');
        });
    });
}

// ==========================================
// INTERSECTION OBSERVER FOR FADE-IN ANIMATIONS
// ==========================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.fade-in').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// ==========================================
// PREVENT DROPDOWN TOGGLE FROM NAVIGATING
// ==========================================
document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
    toggle.addEventListener('click', (e) => {
        if (window.innerWidth > 768) {
            // On desktop, let hover handle it
            return;
        }
    });
});

// ==========================================
// CONSOLE MESSAGE
// ==========================================
console.log('%cDeveloper Ruhban Portfolio', 'color: #7c4dff; font-size: 20px; font-weight: bold;');
console.log('%cBuilt with care from Kashmir 🏔️', 'color: #9fa8da; font-size: 14px;');