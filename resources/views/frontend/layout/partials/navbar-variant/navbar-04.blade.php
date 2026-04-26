<!-- HEADER -->
<header id="home" class="overflow-visible">
    <!-- Navigation -->
    <nav id="mainNav" class="border-b border-gray-200 shadow-sm fixed top-0 left-0 right-0 z-[1000] overflow-visible transition-all duration-300">
        <div class="container mx-auto max-w-8xl flex items-center justify-between px-6 py-4">
            <!-- Logo -->
            <div id="navbarLogo">
                <a href="{{ route('homepage') }}">
                    @if(!empty(get_static_option('site_white_logo')))
                        {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo'), 'w-[157px] site-white-logo hidden') !!}
                    @endif
                    @if(!empty(get_static_option('site_logo')))
                        {!! render_image_markup_by_attachment_id(get_static_option('site_logo'), 'w-[157px] site-dark-logo') !!}
                    @else
                        <img src="{{ asset('assets/static/img/logo/logo.png') }}" alt="site-logo" class="w-[157px] site-dark-logo">
                    @endif
                </a>
            </div>

            <!-- Navigation (Desktop) - NO loading skeleton, will populate instantly -->
            <ul class="hidden lg:flex space-x-8 text-gray-700 font-medium items-center" id="desktopMenu">
                <!-- Menu will be injected by inline script below -->
            </ul>

            <!-- User Menu (Desktop) -->
            <div class="hidden lg:flex items-center space-x-3">
                <x-frontend.user-menu-variant-04 />
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" title="mobile menu" class="lg:hidden block text-gray-600 hover:text-green-600 text-2xl">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Mobile Menu -->
            <div id="mobileMenuArea" class="hidden absolute inset-0 z-[9999]">
                <!-- Overlay -->
                <div id="mobileMenuOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-[99999] min-h-screen"></div>

                <!-- Dropdown -->
                <div id="mobileMenuDropdown" class="lg:hidden fixed top-0 bottom-0 left-0 w-72 bg-white shadow-xl px-8 py-6 overflow-y-auto custom-scrollbar z-[999999] transform -translate-x-full transition-transform duration-300 min-h-screen">
                    <div class="flex items-center justify-between">
                        <!-- Mobile Logo -->
                        <a href="{{ route('homepage') }}" class="mb-6">
                            @if(!empty(get_static_option('site_logo')))
                                {!! render_image_markup_by_attachment_id(get_static_option('site_logo'), 'w-[120px]') !!}
                            @else
                                <img src="{{ asset('assets/static/img/logo/logo.png') }}" alt="site-logo" class="w-[120px]">
                            @endif
                        </a>
                        <!-- Close Button -->
                        <button title="close mobile menu" id="closeMobileMenu" class="text-gray-600 hover:text-red-600 text-xl mb-6">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <!-- Links -->
                    <ul class="flex flex-col space-y-1 text-gray-700 font-medium" id="mobileMenu">
                        <!-- Menu will be injected here by JavaScript -->
                    </ul>

                    <!-- Mobile User Menu -->
                    <div class="flex flex-col gap-4 items-start mt-6 w-full">
                        <x-frontend.user-menu-mobile-variant-04 />
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden menu data -->
        <div id="menuData" style="display: none;">
            {!! render_frontend_menu($primary_menu ?? get_static_option('site_primary_menu')) !!}
        </div>
    </nav>
</header>

<style>
    /* Custom scrollbar styles */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db #f3f4f6;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

<!-- CRITICAL INLINE SCRIPT - Executes IMMEDIATELY for instant menu loading -->
<script>
    (function() {
        'use strict';
        if (window.menuInitialized) return;
        window.menuInitialized = true;

        const menuData = document.getElementById('menuData');
        const originalMenu = menuData?.querySelector('ul');
        if (!originalMenu) return;

        const menuItems = Array.from(originalMenu.children).filter(item => {
            const link = item.querySelector('a');
            return link && link.textContent.trim() !== '';
        });

        // Build Desktop Menu IMMEDIATELY
        const desktopMenu = document.getElementById('desktopMenu');
        if (desktopMenu) {
            const frag = document.createDocumentFragment();
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
                frag.appendChild(li);
            });
            desktopMenu.appendChild(frag);
        }

        // Build Mobile Menu
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu) {
            const frag = document.createDocumentFragment();
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
                    button.innerHTML = `<span class="font-medium text-gray-700">${link.textContent}</span><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 transition-transform duration-300 dropdown-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>`;

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
                frag.appendChild(li);
            });
            mobileMenu.appendChild(frag);
        }

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenuArea = document.getElementById('mobileMenuArea');
        const mobileMenuDropdown = document.getElementById('mobileMenuDropdown');

        mobileMenuBtn?.addEventListener('click', () => {
            mobileMenuArea.classList.remove('hidden');
            requestAnimationFrame(() => mobileMenuDropdown.classList.remove('-translate-x-full'));
        });

        const closeMenu = () => {
            mobileMenuDropdown.classList.add('-translate-x-full');
            setTimeout(() => mobileMenuArea.classList.add('hidden'), 300);
        };

        document.getElementById('closeMobileMenu')?.addEventListener('click', closeMenu);
        document.getElementById('mobileMenuOverlay')?.addEventListener('click', closeMenu);
    })();
</script>

<script>
    (function() {
        'use strict';

        // Function to toggle navbar border
        function toggleNavbarBorder() {
            const navbar = document.getElementById('mainNav');
            const modalsOpen = document.querySelector('#loginModal.flex, #registerModal.flex');

            if (modalsOpen && navbar) {
                navbar.classList.remove('border-b', 'shadow-sm');
            } else if (navbar) {
                navbar.classList.add('border-b', 'shadow-sm');
            }
        }

        // Create a MutationObserver to watch for modal class changes
        const observer = new MutationObserver(toggleNavbarBorder);

        // Observe both modals
        const loginModal = document.getElementById('loginModal');
        const registerModal = document.getElementById('registerModal');

        if (loginModal) {
            observer.observe(loginModal, { attributes: true, attributeFilter: ['class'] });
        }

        if (registerModal) {
            observer.observe(registerModal, { attributes: true, attributeFilter: ['class'] });
        }

        // Initial check
        toggleNavbarBorder();
    })();
</script>