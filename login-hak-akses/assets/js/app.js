/**
 * Liquid Glass Theme - Main JavaScript
 */
document.addEventListener('DOMContentLoaded', function () {

    // ---- Sidebar Toggle (Mobile) ----
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggleBtn = document.querySelector('.mobile-toggle');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('open');
        if (overlay) overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    // Close sidebar on ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSidebar();
            closeAllModals();
        }
    });

    // ---- Modal System ----
    window.openModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function (id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    function closeAllModals() {
        document.querySelectorAll('.modal-overlay.active').forEach(function (m) {
            m.classList.remove('active');
        });
        document.body.style.overflow = '';
    }

    // Close modal when clicking outside
    document.querySelectorAll('.modal-overlay').forEach(function (m) {
        m.addEventListener('click', function (e) {
            if (e.target === m) {
                m.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // ---- Toast System ----
    window.showToast = function (message, type, duration) {
        type = type || 'info';
        duration = duration || 3000;

        var container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        var icons = {
            success: 'ri-check-line',
            danger: 'ri-error-warning-line',
            warning: 'ri-alert-line',
            info: 'ri-information-line'
        };

        var toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        toast.innerHTML = '<i class="' + (icons[type] || icons.info) + '"></i><span>' + message + '</span>';
        container.appendChild(toast);

        setTimeout(function () {
            toast.classList.add('toast-out');
            setTimeout(function () {
                toast.remove();
            }, 300);
        }, duration);
    };

    // ---- Password Toggle ----
    document.querySelectorAll('.password-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = this.previousElementSibling || this.parentElement.querySelector('input[type="password"], input[type="text"]');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="ri-eye-off-line"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="ri-eye-line"></i>';
            }
        });
    });

    // ---- Animated Counters ----
    function animateCounters() {
        document.querySelectorAll('[data-count]').forEach(function (el) {
            var target = parseInt(el.getAttribute('data-count'), 10);
            var current = 0;
            var increment = Math.ceil(target / 40);
            var timer = setInterval(function () {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                el.textContent = current;
            }, 30);
        });
    }

    // Run counters when visible
    var counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                animateCounters();
                counterObserver.disconnect();
            }
        });
    }, { threshold: 0.2 });

    var firstCounter = document.querySelector('[data-count]');
    if (firstCounter) counterObserver.observe(firstCounter);

    // ---- Confirm Delete ----
    window.confirmDelete = function (url, name) {
        var modal = document.getElementById('deleteModal');
        if (!modal) return;
        var nameEl = modal.querySelector('.delete-name');
        var linkEl = modal.querySelector('.delete-confirm-btn');
        if (nameEl) nameEl.textContent = name || '';
        if (linkEl) linkEl.href = url;
        openModal('deleteModal');
    };

    // ---- Search Filter for Table ----
    var searchInput = document.querySelector('.search-filter');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var query = this.value.toLowerCase();
            var rows = document.querySelectorAll('.glass-table tbody tr');
            rows.forEach(function (row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // ---- Notification Dropdown ----
    var notifBtn = document.querySelector('.notif-trigger');
    var notifDrop = document.querySelector('.notification-dropdown');
    if (notifBtn && notifDrop) {
        notifBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            notifDrop.classList.toggle('show');
        });
        document.addEventListener('click', function () {
            notifDrop.classList.remove('show');
        });
    }

    // ---- Active nav link highlight ----
    var currentPage = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.sidebar-nav .nav-link').forEach(function (link) {
        var href = link.getAttribute('href');
        if (href === currentPage) {
            link.classList.add('active');
        }
    });

    // ---- Page load animation ----
    document.querySelectorAll('.page-enter').forEach(function (el) {
        el.style.opacity = '0';
        setTimeout(function () {
            el.style.opacity = '';
        }, 50);
    });

    // ---- Form validation feedback ----
    document.querySelectorAll('form.needs-validation').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            var valid = true;
            form.querySelectorAll('[required]').forEach(function (input) {
                if (!input.value.trim()) {
                    valid = false;
                    input.style.borderColor = 'var(--accent-danger)';
                    input.style.boxShadow = '0 0 0 3px rgba(255, 59, 48, 0.15)';
                } else {
                    input.style.borderColor = '';
                    input.style.boxShadow = '';
                }
            });
            if (!valid) {
                e.preventDefault();
                showToast('Mohon lengkapi semua field yang wajib diisi', 'warning');
            }
        });
    });

    // ---- Smooth scroll for anchor links ----
    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ---- Greeting time-based ----
    var greetEl = document.querySelector('.greeting-text');
    if (greetEl) {
        var hour = new Date().getHours();
        var greeting = '';
        if (hour < 12) greeting = 'Selamat Pagi';
        else if (hour < 15) greeting = 'Selamat Siang';
        else if (hour < 18) greeting = 'Selamat Sore';
        else greeting = 'Selamat Malam';
        greetEl.textContent = greeting;
    }

    // ---- Real-time clock ----
    var clockEl = document.querySelector('.realtime-clock');
    if (clockEl) {
        function updateClock() {
            var now = new Date();
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            var dateStr = now.toLocaleDateString('id-ID', options);
            var timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            clockEl.textContent = dateStr + ' • ' + timeStr;
        }
        updateClock();
        setInterval(updateClock, 30000);
    }
});
