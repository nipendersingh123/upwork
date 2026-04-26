// assets/js/navbar.js - Optimized for INSTANT loading with zero delay
(function() {
    'use strict';

    // Prevent duplicate initialization
    if (window.menuInitialized) return;

    let menuInitialized = false;

    function initializeMenu() {
        if (menuInitialized) return;

        const menuData = document.getElementById('menuData');
        if (!menuData) {
            // Retry after a short delay if menuData not found yet
            if (document.readyState === 'loading') {
                setTimeout(initializeMenu, 10);
            }
            return;
        }

        const originalMenu = menuData.querySelector('ul');
        if (!originalMenu) return;

        // Mark as initialized IMMEDIATELY
        menuInitialized = true;
        window.menuInitialized = true;

        const menuItems = Array.from(originalMenu.children).filter(item => {
            const link = item.querySelector('a');
            return link && link.textContent.trim() !== '';
        });

        // Build Desktop Menu with optimized DOM operations
        const desktopMenu = document.getElementById('desktopMenu');
        if (desktopMenu) {
            // Clear loading skeletons immediately
            desktopMenu.innerHTML = '';

            const desktopFragment = document.createDocumentFragment();

            menuItems.forEach((item) => {
                const link = item.querySelector('a');
                const submenu = item.querySelector('ul');
                if (!link) return;

                const li = document.createElement('li');
                const hasValidSubmenu = submenu && Array.from(submenu.children).some(child => {
                    const childLink = child.querySelector('a');
                    return childLink && childLink.textContent.trim() !== '';
                });

                if (hasValidSubmenu) {
                    li.className = 'flex items-center gap-1 group relative';

                    const linkClone = link.cloneNode(true);
                    linkClone.className = 'hover:text-green-600 transition font-medium';

                    const arrow = document.createElement('span');
                    arrow.innerHTML = '<svg class="w-4 h-4 text-gray-500 group-hover:text-green-600 transition duration-300 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>';

                    const dropdown = document.createElement('div');
                    dropdown.className = 'absolute top-full left-0 mt-0 w-56 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50 overflow-hidden';

                    const dropdownUl = document.createElement('ul');
                    dropdownUl.className = 'py-2 max-h-[400px] overflow-y-auto custom-scrollbar';

                    Array.from(submenu.children).forEach(subItem => {
                        const subLink = subItem.querySelector('a');
                        if (!subLink || subLink.textContent.trim() === '') return;

                        const subLinkClone = subLink.cloneNode(true);
                        subLinkClone.className = 'block px-5 py-2.5 text-sm text-gray-600 hover:text-primary hover:bg-primary/5 transition-colors';
                        const subLi = document.createElement('li');
                        subLi.appendChild(subLinkClone);
                        dropdownUl.appendChild(subLi);
                    });

                    dropdown.appendChild(dropdownUl);
                    li.appendChild(linkClone);
                    li.appendChild(arrow.firstChild);
                    li.appendChild(dropdown);
                } else {
                    const linkClone = link.cloneNode(true);
                    linkClone.className = 'hover:text-green-600 transition';
                    li.appendChild(linkClone);
                }

                desktopFragment.appendChild(li);
            });

            // Single DOM update
            desktopMenu.appendChild(desktopFragment);
            desktopMenu.classList.add('menu-loaded');
        }

        // Build Mobile Menu
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu) {
            const mobileFragment = document.createDocumentFragment();

            menuItems.forEach((item) => {
                const link = item.querySelector('a');
                const submenu = item.querySelector('ul');
                if (!link) return;

                const li = document.createElement('li');
                li.className = 'border-b border-gray-100';

                const hasValidSubmenu = submenu && Array.from(submenu.children).some(child => {
                    const childLink = child.querySelector('a');
                    return childLink && childLink.textContent.trim() !== '';
                });

                if (hasValidSubmenu) {
                    const button = document.createElement('button');
                    button.className = 'mobile-dropdown-btn w-full flex items-center justify-between py-3 px-2 rounded-lg hover:bg-gray-50 transition-colors text-left';
                    button.innerHTML = `
                        <span class="font-medium text-gray-700">${link.textContent}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 transition-transform duration-300 dropdown-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    `;

                    const dropdownContent = document.createElement('div');
                    dropdownContent.className = 'mobile-dropdown-content hidden';

                    const subList = document.createElement('ul');
                    subList.className = 'pl-4 py-2 space-y-1';

                    Array.from(submenu.children).forEach(subItem => {
                        const subLink = subItem.querySelector('a');
                        if (!subLink || subLink.textContent.trim() === '') return;

                        const subLinkClone = subLink.cloneNode(true);
                        subLinkClone.className = 'block py-2 px-3 text-sm text-gray-600 hover:text-primary hover:bg-primary/5 rounded-md transition-colors';
                        const subLi = document.createElement('li');
                        subLi.appendChild(subLinkClone);
                        subList.appendChild(subLi);
                    });

                    dropdownContent.appendChild(subList);

                    button.addEventListener('click', () => {
                        const arrow = button.querySelector('.dropdown-icon');
                        if (dropdownContent.classList.contains('hidden')) {
                            dropdownContent.classList.remove('hidden');
                            arrow.style.transform = 'rotate(180deg)';
                        } else {
                            dropdownContent.classList.add('hidden');
                            arrow.style.transform = 'rotate(0deg)';
                        }
                    });

                    li.appendChild(button);
                    li.appendChild(dropdownContent);
                } else {
                    const linkClone = link.cloneNode(true);
                    linkClone.className = 'block py-3 px-2 rounded-lg hover:bg-gray-50 transition-colors';
                    li.appendChild(linkClone);
                }

                mobileFragment.appendChild(li);
            });

            mobileMenu.appendChild(mobileFragment);
        }

        // Mobile Menu Toggle with event delegation for better performance
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenuArea = document.getElementById('mobileMenuArea');
        const mobileMenuDropdown = document.getElementById('mobileMenuDropdown');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

        if (mobileMenuBtn && mobileMenuArea && mobileMenuDropdown) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenuArea.classList.remove('hidden');
                requestAnimationFrame(() => {
                    mobileMenuDropdown.classList.remove('-translate-x-full');
                });
            });

            const closeMenu = () => {
                mobileMenuDropdown.classList.add('-translate-x-full');
                setTimeout(() => {
                    mobileMenuArea.classList.add('hidden');
                }, 300);
            };

            closeMobileMenu?.addEventListener('click', closeMenu);
            mobileMenuOverlay?.addEventListener('click', closeMenu);
        }

        // Dispatch event
        document.dispatchEvent(new CustomEvent('menuInitialized'));
    }

    // Execute IMMEDIATELY - check multiple times for fastest initialization
    if (document.readyState === 'loading') {
        // Try to initialize as soon as possible
        document.addEventListener('DOMContentLoaded', initializeMenu);
        // Also try during interactive state
        document.addEventListener('readystatechange', function() {
            if (document.readyState === 'interactive' || document.readyState === 'complete') {
                initializeMenu();
            }
        });
    } else {
        // DOM is already ready, execute NOW
        initializeMenu();
    }
})();