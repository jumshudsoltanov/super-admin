// Navigation Menu Toggle
const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('nav-toggle');
const navClose = document.getElementById('nav-close');

// Show menu
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu');
    });
}

// Hide menu
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
    });
}

// Close menu when clicking on nav links
const navLinks = document.querySelectorAll('.nav__link');

navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('show-menu');
    });
});

// Active Link on Scroll
const sections = document.querySelectorAll('section[id]');

function scrollActive() {
    const scrollY = window.pageYOffset;

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight;
        const sectionTop = current.offsetTop - 100;
        const sectionId = current.getAttribute('id');
        const link = document.querySelector('.nav__link[href*=' + sectionId + ']');

        if (link) {
            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                link.classList.add('active-link');
            } else {
                link.classList.remove('active-link');
            }
        }
    });
}

window.addEventListener('scroll', scrollActive);

// Scroll Header Shadow
function scrollHeader() {
    const header = document.getElementById('header');
    if (this.scrollY >= 50) {
        header.classList.add('scroll-header');
    } else {
        header.classList.remove('scroll-header');
    }
}

window.addEventListener('scroll', scrollHeader);

// Show Scroll Up Button
function scrollUp() {
    const scrollUp = document.getElementById('scroll-up');
    if (this.scrollY >= 400) {
        scrollUp.classList.add('show-scroll');
    } else {
        scrollUp.classList.remove('show-scroll');
    }
}

window.addEventListener('scroll', scrollUp);

// Smooth Scroll for All Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Contact Form Handling
const contactForm = document.getElementById('contact-form');

if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            subject: document.getElementById('subject').value,
            message: document.getElementById('message').value
        };

        // Here you would typically send the data to your PHP backend
        // For now, we'll just log it and show a success message
        console.log('Form Data:', formData);
        
        // Show success message (you can customize this)
        alert('Mesajınız göndərildi! Tezliklə sizinlə əlaqə saxlayacağıq.');
        
        // Reset form
        contactForm.reset();
        
        // When you integrate with PHP, you would do something like:
        /*
        fetch('process-form.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mesajınız göndərildi!');
                contactForm.reset();
            } else {
                alert('Xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.');
        });
        */
    });
}

// Form Input Focus Animation
const formInputs = document.querySelectorAll('.form__input');

formInputs.forEach(input => {
    // Add focus class on input
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('focus');
    });

    // Remove focus class on blur if input is empty
    input.addEventListener('blur', function() {
        if (this.value === '') {
            this.parentElement.classList.remove('focus');
        }
    });

    // Keep focus class if input has value
    if (input.value !== '') {
        input.parentElement.classList.add('focus');
    }
});

// Scroll Reveal Animation (Optional Enhancement)
// This adds a fade-in effect to elements as they come into view
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for animation
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll(
        '.product__card, .feature__item, .hero__content, .about__content'
    );
    
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(element);
    });
});

// Prevent default form submission for demo purposes
// Remove this when integrating with PHP backend
document.querySelectorAll('form').forEach(form => {
    if (!form.id) return; // Only handle forms with IDs
    
    form.addEventListener('submit', function(e) {
        // This prevents page reload during development
        // Remove or modify this when you add PHP backend
        const formId = this.id;
        if (formId === 'contact-form') {
            // Already handled above
            return;
        }
    });
});

// Stats Counter Animation (for hero section)
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16); // 60fps
    
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target + (element.dataset.suffix || '');
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start) + (element.dataset.suffix || '');
        }
    }, 16);
}

// Trigger counter animation when stats section is visible
const statsObserver = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
            entry.target.classList.add('counted');
            const numbers = entry.target.querySelectorAll('.stat__number');
            
            numbers.forEach(number => {
                const text = number.textContent;
                const numericValue = parseInt(text.replace(/\D/g, ''));
                const suffix = text.replace(/[\d.]/g, '');
                
                number.dataset.suffix = suffix;
                number.textContent = '0' + suffix;
                
                animateCounter(number, numericValue);
            });
        }
    });
}, { threshold: 0.5 });

// Observe hero stats
const heroStats = document.querySelector('.hero__stats');
if (heroStats) {
    statsObserver.observe(heroStats);
}

// Product Card Tilt Effect (Optional Enhancement)
document.querySelectorAll('.product__card').forEach(card => {
    card.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const rotateX = (y - centerY) / 20;
        const rotateY = (centerX - x) / 20;
        
        this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
    });
});

console.log('Unipos Az - Website loaded successfully! 🚀');
