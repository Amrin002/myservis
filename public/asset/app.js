 // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('SW registered: ', registration);
                    })
                    .catch(function(registrationError) {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }

        // PWA Install Banner
        let deferredPrompt;
        const installBanner = document.getElementById('installBanner');
        const installBtn = document.getElementById('installBtn');
        const dismissBtn = document.getElementById('dismissBtn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installBanner.classList.remove('d-none');
        });

        installBtn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const {
                    outcome
                } = await deferredPrompt.userChoice;
                console.log(`User response to the install prompt: ${outcome}`);
                deferredPrompt = null;
                installBanner.classList.add('d-none');
            }
        });

        dismissBtn.addEventListener('click', () => {
            installBanner.classList.add('d-none');
            deferredPrompt = null;
        });

        // Hide banner if app is already installed
        window.addEventListener('appinstalled', () => {
            installBanner.classList.add('d-none');
        });

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');

            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // For mobile
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            }
        }

        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (bootstrap.Alert.getInstance(alert)) {
                    const bsAlert = bootstrap.Alert.getInstance(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const toggleButton = event.target.closest('[onclick="toggleSidebar()"]');

                if (!sidebar.contains(event.target) && !toggleButton) {
                    sidebar.classList.remove('show');
                    document.getElementById('main-content').classList.remove('expanded');
                }
            }
        });

        // Handle responsive sidebar
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');

            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                if (!sidebar.classList.contains('collapsed')) {
                    mainContent.classList.remove('expanded');
                }
            }
        });