// Modern JavaScript for MTEULE Ventures
class MTEULEApp {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupHeaderHide();
        this.setupCounters();
        this.setupAnimations();
        this.setupServiceCards();
        this.setupPageTransitions();
        this.setupFloatingCTAs();
    }
    
    setupHeaderHide() {
        let lastScroll = 0;
        const header = document.getElementById('header');
        
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            // Add background when scrolled
            if (currentScroll > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            
            // Hide header on scroll down, show on scroll up
            if (currentScroll > lastScroll && currentScroll > 200) {
                header.classList.add('hidden');
            } else {
                header.classList.remove('hidden');
            }
            
            lastScroll = currentScroll;
        });
    }
    
    setupCounters() {
        // Animate counter numbers
        const animateCounter = (element, target, duration = 2000) => {
            let start = 0;
            const increment = target / (duration / 16);
            const timer = setInterval(() => {
                start += increment;
                if (start >= target) {
                    element.textContent = target + (element.id.includes('satisfaction') ? '%' : '');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(start) + (element.id.includes('satisfaction') ? '%' : '');
                }
            }, 16);
        };
        
        // Initialize when in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const target = parseInt(element.dataset.count || element.textContent);
                    if (!element.animated) {
                        animateCounter(element, target);
                        element.animated = true;
                    }
                }
            });
        }, { threshold: 0.5 });
        
        // Observe all counter elements
        document.querySelectorAll('.stat-number, .dashboard-value').forEach(el => {
            observer.observe(el);
        });
    }
    
    setupAnimations() {
        // Create floating particles
        this.createParticles();
        
        // Add scroll animations
        this.setupScrollAnimations();
    }
    
    createParticles() {
        const particlesContainer = document.querySelector('.particles') || document.body;
        const colors = ['#7c3aed', '#f59e0b', '#10b981', '#3b82f6'];
        
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.position = 'absolute';
            particle.style.width = Math.random() * 5 + 2 + 'px';
            particle.style.height = particle.style.width;
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.background = colors[Math.floor(Math.random() * colors.length)];
            particle.style.borderRadius = '50%';
            particle.style.opacity = '0.3';
            particle.style.zIndex = '-1';
            particle.style.animation = `float ${Math.random() * 20 + 15}s infinite linear`;
            particle.style.animationDelay = Math.random() * 5 + 's';
            
            particlesContainer.appendChild(particle);
        }
    }
    
    setupScrollAnimations() {
        // Add parallax effect to hero
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.1}px)`;
            }
        });
        
        // Animate elements on scroll
        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('.service-card, .info-card, .floating-card').forEach(el => {
            scrollObserver.observe(el);
        });
    }
    
    setupServiceCards() {
        // Add hover effects to service cards
        document.querySelectorAll('.service-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px)';
                card.style.boxShadow = '0 25px 50px rgba(0,0,0,0.25)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '0 5px 15px rgba(0,0,0,0.05)';
            });
        });
    }
    
    setupPageTransitions() {
        // Smooth page transitions
        document.querySelectorAll('a:not([href^="#"])').forEach(link => {
            link.addEventListener('click', (e) => {
                if (link.href && !link.href.includes('javascript:')) {
                    e.preventDefault();
                    const transition = document.getElementById('pageTransition');
                    if (transition) {
                        transition.classList.add('active');
                        setTimeout(() => {
                            window.location.href = link.href;
                        }, 600);
                    } else {
                        window.location.href = link.href;
                    }
                }
            });
        });
        
        // Remove transition on page load
        window.addEventListener('load', () => {
            const transition = document.getElementById('pageTransition');
            if (transition) {
                transition.classList.remove('active');
            }
        });
    }
    
    setupFloatingCTAs() {
        // WhatsApp button
        const whatsappBtn = document.getElementById('whatsappBtn');
        if (whatsappBtn) {
            whatsappBtn.addEventListener('click', () => {
                window.open('https://wa.me/254712345678', '_blank');
            });
        }
        
        // Call button
        const callBtn = document.getElementById('callBtn');
        if (callBtn) {
            callBtn.addEventListener('click', () => {
                window.location.href = 'tel:+254712345678';
            });
        }
        
        // Scroll to top button
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        if (scrollTopBtn) {
            scrollTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            // Show/hide based on scroll
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    scrollTopBtn.style.opacity = '1';
                    scrollTopBtn.style.visibility = 'visible';
                } else {
                    scrollTopBtn.style.opacity = '0';
                    scrollTopBtn.style.visibility = 'hidden';
                }
            });
        }
    }
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.app = new MTEULEApp();
    
    // Set current year in footer
    const yearElement = document.getElementById('currentYear');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }
    
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            navMenu.classList.toggle('show');
        });
    }
    
    // Quick order form
    const quickOrderForm = document.querySelector('.quick-order-form');
    if (quickOrderForm) {
        quickOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const priceResult = document.getElementById('priceResult');
            if (priceResult) {
                priceResult.style.display = 'block';
                priceResult.scrollIntoView({ behavior: 'smooth' });
                
                // Animate price calculation
                const priceElement = document.getElementById('calculatedPrice');
                let price = 250;
                let current = 0;
                const increment = price / 20;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= price) {
                        priceElement.textContent = price;
                        clearInterval(timer);
                    } else {
                        priceElement.textContent = Math.floor(current);
                    }
                }, 50);
            }
        });
    }
    
    // Tracking functionality
    const trackBtn = document.getElementById('trackBtn');
    if (trackBtn) {
        trackBtn.addEventListener('click', function() {
            const trackingNum = document.getElementById('trackingNumber')?.value.trim();
            const resultDiv = document.getElementById('trackingResult');
            
            if (trackingNum && resultDiv) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Tracking...';
                this.disabled = true;
                
                setTimeout(() => {
                    resultDiv.style.display = 'block';
                    this.innerHTML = '<i class="fas fa-search"></i> Track Package';
                    this.disabled = false;
                    resultDiv.scrollIntoView({ behavior: 'smooth' });
                    
                    // Animate progress steps
                    const steps = document.querySelectorAll('.step');
                    steps.forEach(step => step.classList.remove('active'));
                    
                    let currentStep = 0;
                    const animateSteps = () => {
                        if (currentStep < steps.length) {
                            steps[currentStep].classList.add('active');
                            currentStep++;
                            setTimeout(animateSteps, 800);
                        }
                    };
                    animateSteps();
                }, 1500);
            }
        });
    }
});

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .animate-in {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .service-card, .info-card, .floating-card {
        opacity: 0;
    }
    
    .service-card.animate-in, 
    .info-card.animate-in, 
    .floating-card.animate-in {
        opacity: 1;
    }
`;
document.head.appendChild(style);
