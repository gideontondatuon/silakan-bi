<div class="menu-section">
    MENU
</div>


<a href="<?php echo e(route('user.dashboard')); ?>"
   class="<?php echo e(request()->routeIs('user.dashboard') ? 'active' : ''); ?>">

    <i class="bi bi-grid-fill"></i>

    <span>
        Dashboard
    </span>

</a>


<a href="<?php echo e(route('pemesanan.create')); ?>"
   class="<?php echo e(request()->routeIs('pemesanan.create') ? 'active' : ''); ?>">

    <i class="bi bi-calendar-plus"></i>

    <span>
        Pemesanan
    </span>

</a>


<a href="<?php echo e(route('kalender.index')); ?>"
   class="<?php echo e(request()->routeIs('kalender.*') ? 'active' : ''); ?>">

    <i class="bi bi-calendar3"></i>

    <span>
        Kalender Ruangan
    </span>

</a>


<a href="<?php echo e(route('pemesanan.index')); ?>"
   class="<?php echo e(request()->routeIs('pemesanan.index', 'pemesanan.show') ? 'active' : ''); ?>">

    <i class="bi bi-clock-history"></i>

    <span>
        Riwayat
    </span>

</a>


<a href="<?php echo e(route('notifications.index')); ?>"
   class="<?php echo e(request()->routeIs('notifications.*', 'notification.*') ? 'active' : ''); ?>">

    <i class="bi bi-bell"></i>

    <span>
        Notifikasi
    </span>


    <?php
        $unreadNotification = auth()
            ->user()
            ->unreadNotifications()
            ->count();
    ?>


    <?php if($unreadNotification > 0): ?>

        <small class="sidebar-badge">
            <?php echo e($unreadNotification); ?>

        </small>

    <?php endif; ?>

</a>


<a href="<?php echo e(route('profile.edit')); ?>"
   class="<?php echo e(request()->routeIs('profile.*') ? 'active' : ''); ?>">

    <i class="bi bi-person-circle"></i>

    <span>
        Profil
    </span>

</a><?php /**PATH D:\Bank Indo\silakan\resources\views/components/sidebar/user.blade.php ENDPATH**/ ?>