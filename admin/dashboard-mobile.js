// dashboard-mobile.js - Mobile-specific functionality and theme toggle

(function() {
    'use strict';

    // Theme management
    const ThemeManager = {
        init() {
            // Load saved theme or default to light
            const savedTheme = localStorage.getItem('admin-theme') || 'light';
            this.setTheme(savedTheme);

            // Setup theme toggle button
            const toggleBtn = document.getElementById('themeToggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => this.toggle());
            }
        },

        setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('admin-theme', theme);

            // Update toggle button icon
            const toggleBtn = document.getElementById('themeToggle');
            if (toggleBtn) {
                toggleBtn.textContent = theme === 'dark' ? '☀️' : '🌙';
                toggleBtn.setAttribute('aria-label', theme === 'dark' ? 'Växla till ljust tema' : 'Växla till mörkt tema');
            }
        },

        toggle() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            this.setTheme(newTheme);
        },

        getCurrentTheme() {
            return document.documentElement.getAttribute('data-theme') || 'light';
        }
    };

    // Accordion functionality for mobile
    const Accordion = {
        init() {
            const accordionHeaders = document.querySelectorAll('.accordion-header');
            accordionHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    // Only toggle on mobile
                    if (window.innerWidth > 768) {
                        return; // Desktop: accordions are always open
                    }

                    const content = header.nextElementSibling;
                    const isOpen = content.classList.contains('active');

                    // Close all accordions
                    document.querySelectorAll('.accordion-content').forEach(item => {
                        item.classList.remove('active');
                    });
                    document.querySelectorAll('.accordion-header').forEach(item => {
                        item.classList.remove('active');
                    });

                    // Toggle current
                    if (!isOpen) {
                        header.classList.add('active');
                        content.classList.add('active');

                        // Trigger chart loading if needed
                        const chartContainers = content.querySelectorAll('.chart-container[data-lazy]');
                        chartContainers.forEach(container => {
                            const chartId = container.getAttribute('data-chart-id');
                            if (chartId) {
                                const event = new CustomEvent('loadChart', {
                                    detail: { chartId, container }
                                });
                                document.dispatchEvent(event);
                            }
                        });
                    }
                });
            });

            // On desktop, all accordions should be open by default
            if (window.innerWidth > 768) {
                document.querySelectorAll('.accordion-content').forEach(content => {
                    content.classList.add('active');
                });
                document.querySelectorAll('.accordion-header').forEach(header => {
                    header.classList.add('active');
                });
            }

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if (window.innerWidth > 768) {
                        // Desktop: open all
                        document.querySelectorAll('.accordion-content').forEach(content => {
                            content.classList.add('active');
                        });
                        document.querySelectorAll('.accordion-header').forEach(header => {
                            header.classList.add('active');
                        });
                    }
                }, 250);
            });
        }
    };

    // Mobile menu toggle
    const MobileMenu = {
        init() {
            const menuToggle = document.getElementById('mobileMenuToggle');
            const menu = document.getElementById('mobileMenu');

            if (menuToggle && menu) {
                menuToggle.addEventListener('click', () => {
                    menu.classList.toggle('active');
                    menuToggle.setAttribute('aria-expanded', menu.classList.contains('active'));
                });

                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                    if (!menu.contains(e.target) && !menuToggle.contains(e.target)) {
                        menu.classList.remove('active');
                        menuToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        }
    };

    // Touch-friendly interactions
    const TouchOptimizer = {
        init() {
            // Add touch class to body on touch devices
            if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
                document.body.classList.add('touch-device');
            }

            // Improve button touch targets
            const buttons = document.querySelectorAll('button, .btn, a.btn');
            buttons.forEach(btn => {
                if (btn.offsetHeight < 44 || btn.offsetWidth < 44) {
                    btn.style.minHeight = '44px';
                    btn.style.minWidth = '44px';
                    btn.style.padding = '0.75rem 1rem';
                }
            });
        }
    };

    // Lazy load charts
    const ChartLoader = {
        init() {
            // Use Intersection Observer for lazy loading
            if ('IntersectionObserver' in window) {
                const chartContainers = document.querySelectorAll('.chart-container[data-lazy]');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const container = entry.target;
                            const chartId = container.getAttribute('data-chart-id');
                            if (chartId && !container.dataset.loaded) {
                                this.loadChart(chartId, container);
                                container.dataset.loaded = 'true';
                                observer.unobserve(container);
                            }
                        }
                    });
                }, {
                    rootMargin: '50px'
                });

                chartContainers.forEach(container => {
                    observer.observe(container);
                });
            }
        },

        loadChart(chartId, container) {
            // This will be called when chart container is visible
            // The actual chart loading will be handled by the main dashboard script
            const event = new CustomEvent('loadChart', {
                detail: { chartId, container }
            });
            document.dispatchEvent(event);
        }
    };

    // Initialize everything when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        ThemeManager.init();
        Accordion.init();
        MobileMenu.init();
        TouchOptimizer.init();
        ChartLoader.init();
    });

    // Expose ThemeManager globally for use in other scripts
    window.ThemeManager = ThemeManager;

    // Listen for theme changes and dispatch event
    const originalSetTheme = ThemeManager.setTheme;
    ThemeManager.setTheme = function(theme) {
        originalSetTheme.call(this, theme);
        document.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    };
})();

