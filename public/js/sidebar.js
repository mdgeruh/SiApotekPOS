// Sidebar Management Script
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarToggleTop = document.getElementById('sidebarToggleTop');
    const sidebar = document.querySelector('.sidebar');
    const contentWrapper = document.querySelector('.content-wrapper');
    const body = document.body;

    // Check if sidebar was previously toggled
    const sidebarState = localStorage.getItem('sidebarToggled');
    if (sidebarState === 'true' && window.innerWidth > 768) {
        sidebar.classList.add('toggled');
        contentWrapper.classList.add('toggled');
        body.classList.add('sidebar-toggled');
    }

    // Desktop sidebar toggle
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            sidebar.classList.toggle('toggled');
            contentWrapper.classList.toggle('toggled');
            body.classList.toggle('sidebar-toggled');

            // Save state to localStorage
            const isToggled = sidebar.classList.contains('toggled');
            localStorage.setItem('sidebarToggled', isToggled);
        });
    }

    // Mobile sidebar toggle
    if (sidebarToggleTop) {
        sidebarToggleTop.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Toggle mobile sidebar
            sidebar.classList.toggle('show');

            // Add overlay for mobile
            if (sidebar.classList.contains('show')) {
                addMobileOverlay();
            } else {
                removeMobileOverlay();
            }
        });
    }

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !sidebarToggleTop.contains(e.target)) {
                sidebar.classList.remove('show');
                removeMobileOverlay();
            }
        }
    });

    // Function to add mobile overlay
    function addMobileOverlay() {
        if (!document.getElementById('mobileOverlay')) {
            const overlay = document.createElement('div');
            overlay.id = 'mobileOverlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1049;
                cursor: pointer;
            `;
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                removeMobileOverlay();
            });
            document.body.appendChild(overlay);
        }
    }

    // Function to remove mobile overlay
    function removeMobileOverlay() {
        const overlay = document.getElementById('mobileOverlay');
        if (overlay) {
            overlay.remove();
        }
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
            removeMobileOverlay();

            // Restore sidebar state on desktop
            const sidebarState = localStorage.getItem('sidebarToggled');
            if (sidebarState === 'true') {
                sidebar.classList.add('toggled');
                contentWrapper.classList.add('toggled');
                body.classList.add('sidebar-toggled');
            }
        } else {
            // Reset sidebar state on mobile
            sidebar.classList.remove('toggled');
            contentWrapper.classList.remove('toggled');
            body.classList.remove('sidebar-toggled');
        }
    });

        // Initialize Bootstrap dropdowns
    function initializeDropdowns() {
        console.log('Initializing dropdowns...');

        if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
            console.log('Bootstrap Dropdown available');

            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            console.log('Found dropdown elements:', dropdownElementList.length);

            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                try {
                    const dropdown = new bootstrap.Dropdown(dropdownToggleEl, {
                        autoClose: false,
                        boundary: 'viewport'
                    });

                                        // Add custom event listeners for better control
                    dropdownToggleEl.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Close other dropdowns first
                        const otherDropdowns = document.querySelectorAll('.sidebar .dropdown-menu.show');
                        otherDropdowns.forEach(function(dropdown) {
                            if (dropdown !== this.nextElementSibling) {
                                dropdown.classList.remove('show');
                                const otherToggle = dropdown.previousElementSibling;
                                if (otherToggle) {
                                    otherToggle.setAttribute('aria-expanded', 'false');
                                }
                            }
                        });

                        const dropdownMenu = this.nextElementSibling;
                        if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                            const isExpanded = this.getAttribute('aria-expanded') === 'true';
                            this.setAttribute('aria-expanded', !isExpanded);

                            if (isExpanded) {
                                dropdownMenu.classList.remove('show');
                            } else {
                                dropdownMenu.classList.add('show');
                            }
                        }
                    });

                    console.log('Dropdown initialized for:', dropdownToggleEl.id);
                    return dropdown;
                } catch (error) {
                    console.error('Error initializing dropdown for:', dropdownToggleEl.id, error);
                    return null;
                }
            });

            console.log('Dropdowns initialized:', dropdownList.filter(d => d !== null).length);
        } else {
            console.warn('Bootstrap Dropdown not available');

            // Fallback: manual dropdown toggle
            var dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                        const isExpanded = this.getAttribute('aria-expanded') === 'true';
                        this.setAttribute('aria-expanded', !isExpanded);

                        if (isExpanded) {
                            dropdownMenu.classList.remove('show');
                        } else {
                            dropdownMenu.classList.add('show');
                        }
                    }
                });
            });
        }
    }

    // Initialize dropdowns after a short delay to ensure DOM is ready
    setTimeout(initializeDropdowns, 100);

    // Debug: Log sidebar elements
    console.log('Sidebar elements found:', {
        sidebar: sidebar,
        sidebarToggle: sidebarToggle,
        sidebarToggleTop: sidebarToggleTop,
        contentWrapper: contentWrapper
    });

    // Debug: Check for dropdown elements
    const dropdownElements = document.querySelectorAll('.dropdown-toggle');
    console.log('Dropdown elements found:', dropdownElements.length);
    dropdownElements.forEach((el, index) => {
        console.log(`Dropdown ${index + 1}:`, {
            id: el.id,
            classList: el.classList.toString(),
            ariaExpanded: el.getAttribute('aria-expanded'),
            nextElement: el.nextElementSibling?.classList.toString()
        });
    });
});
