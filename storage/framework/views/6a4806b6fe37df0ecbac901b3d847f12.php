<header class="navbar">

    <div class="navbar-left">
        <button class="menu-toggle" id="sidebarToggle" title="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>

        
        <a href="<?php echo e(route('dashboard')); ?>" class="mobile-brand">
            <img src="<?php echo e(asset('images/logo-bi4.png')); ?>" alt="Bank Indonesia Logo" class="mobile-brand-logo">
            <div class="mobile-brand-text">
                <span class="mobile-brand-title">SILAKAN</span>
                <span class="mobile-brand-sub">KPwBI Prov. Sulut</span>
            </div>
        </a>

        <div class="navbar-page-info" id="navbar-page-info">
            <span class="navbar-page-title" id="navbar-page-title">SILAKAN</span>
            <div class="navbar-breadcrumb" id="navbar-breadcrumb">
                <span>Dashboard</span>
            </div>
        </div>
    </div>

    <div class="navbar-right">

        
        <div class="navbar-clock" id="navbar-clock">
            <span class="navbar-clock-time" id="navbar-time">--:--:--</span>
            <span class="navbar-clock-date" id="navbar-date">-- --- ----</span>
        </div>

        
        <div class="notification dropdown">
            <button class="notification-button" onclick="toggleNotification()" title="Notifikasi">
                <i class="bi bi-bell"></i>
                <?php if($notifications->count() > 0): ?>
                    <span class="notification-count"><?php echo e($notifications->count()); ?></span>
                <?php endif; ?>
            </button>

            <div class="notification-panel" id="notificationPanel">
                <div class="notification-header">
                    <span><i class="bi bi-bell-fill" style="color:#005baa;margin-right:6px;"></i> Notifikasi</span>
                    <?php if($notifications->count() > 0): ?>
                        <span class="badge badge-primary"><?php echo e($notifications->count()); ?> Baru</span>
                    <?php endif; ?>
                </div>

                <?php if($notifications->count()): ?>
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('notification.read', $notification->id)); ?>" class="notification-item">
                            <div class="notification-icon">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <div class="notification-content">
                                <strong><?php echo e($notification->data['judul']); ?></strong>
                                <p><?php echo e($notification->data['pesan']); ?></p>
                                <small><i class="bi bi-clock"></i> <?php echo e($notification->data['waktu']); ?></small>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="notification-empty">
                        <i class="bi bi-bell-slash"></i>
                        <p>Tidak ada notifikasi baru.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="profile">
            <a href="<?php echo e(route('profile.edit')); ?>" class="profile-link" title="Lihat Profil">
                <div class="profile-avatar" style="<?php echo e(auth()->user()->avatar_style); ?>">
                    <?php echo e(auth()->user()->initials); ?>

                </div>
                <div class="profile-info">
                    <strong><?php echo e(auth()->user()->name); ?></strong>
                    <small><?php echo e(auth()->user()->role->value === 'admin' ? 'Administrator' : 'User'); ?></small>
                </div>
            </a>
            <div class="profile-menu">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

    </div>

</header>


<script>
/* =========================================
   Real-time Clock
   ========================================= */
function updateClock() {
    const now = new Date();
    const timeEl = document.getElementById('navbar-time');
    const dateEl = document.getElementById('navbar-date');
    if (!timeEl || !dateEl) return;

    const hours   = String(now.getHours()).padStart(2,'0');
    const minutes = String(now.getMinutes()).padStart(2,'0');
    const seconds = String(now.getSeconds()).padStart(2,'0');
    timeEl.textContent = `${hours}:${minutes}:${seconds} WITA`;

    const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    dateEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
}
updateClock();
setInterval(updateClock, 1000);

/* =========================================
   Notification Dropdown
   ========================================= */
function toggleNotification() {
    const panel = document.getElementById('notificationPanel');
    if (panel) panel.classList.toggle('show');
}

document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.notification');
    const panel    = document.getElementById('notificationPanel');
    if (dropdown && panel && !dropdown.contains(event.target)) {
        panel.classList.remove('show');
    }
});

/* =========================================
   Sidebar Toggle — Desktop + Mobile
   ========================================= */
const sidebarToggle = document.getElementById('sidebarToggle');
if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            document.body.classList.toggle('mobile-sidebar-open');
        } else {
            document.body.classList.toggle('sidebar-collapsed');
        }
    });
}

/* =========================================
   Close Mobile Sidebar (overlay click)
   ========================================= */
const sidebarOverlay = document.querySelector('.sidebar-overlay');
if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', function() {
        document.body.classList.remove('mobile-sidebar-open');
    });
}

document.querySelectorAll('.sidebar-menu a').forEach(function(link) {
    link.addEventListener('click', function() {
        document.body.classList.remove('mobile-sidebar-open');
    });
});

/* =========================================
   Dynamic Navbar Page Title
   Reads the active sidebar link text
   ========================================= */
document.addEventListener('DOMContentLoaded', function() {
    const activeLink = document.querySelector('.sidebar-menu a.active');
    const titleEl    = document.getElementById('navbar-page-title');
    const breadEl    = document.getElementById('navbar-breadcrumb');
    if (activeLink && titleEl) {
        const spanText = activeLink.querySelector('span');
        if (spanText) {
            titleEl.textContent = spanText.textContent.trim();
            if (breadEl) {
                breadEl.innerHTML = `<span>SILAKAN</span><span>${spanText.textContent.trim()}</span>`;
            }
        }
    }
});
</script><?php /**PATH D:\Bank Indo\silakan\resources\views/components/navbar.blade.php ENDPATH**/ ?>