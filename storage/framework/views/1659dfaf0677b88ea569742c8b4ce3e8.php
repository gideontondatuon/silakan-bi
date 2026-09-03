<div class="menu-section">
    UTAMA
</div>

<a href="<?php echo e(route('admin.dashboard')); ?>"
   class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">

    <i class="bi bi-grid-fill"></i>

    <span>
        Dashboard
    </span>

</a>


<a href="<?php echo e(route('admin.approval.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.approval.*') ? 'active' : ''); ?>">
    <i class="bi bi-calendar-check"></i>
    <span>
        Pemesanan Ruangan
    </span>
    <?php
        $pendingBookingCount = \App\Models\Pemesanan::where('status', 'Pending')->count();
    ?>
    <?php if($pendingBookingCount > 0): ?>
        <small class="sidebar-badge" style="background:#f59e0b;color:#fff;">
            <?php echo e($pendingBookingCount); ?>

        </small>
    <?php endif; ?>
</a>


<a href="<?php echo e(route('admin.kegiatan-berlangsung.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.kegiatan-berlangsung.*') ? 'active' : ''); ?>">

    <i class="bi bi-play-circle"></i>

    <span>
        Kegiatan Berlangsung
    </span>

</a>


<a href="<?php echo e(route('kalender.index')); ?>"
   class="<?php echo e(request()->routeIs('kalender.*') ? 'active' : ''); ?>">

    <i class="bi bi-calendar3"></i>

    <span>
        Kalender Ruangan
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


<div class="menu-section">
    MASTER <br> DATA
</div>


<a href="<?php echo e(route('admin.ruangan.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.ruangan.*') ? 'active' : ''); ?>">

    <i class="bi bi-building"></i>

    <span>
        Data Ruangan
    </span>

</a>

<a href="<?php echo e(route('admin.layout.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.layout.*') ? 'active' : ''); ?>">

    <i class="bi bi-layout-text-sidebar-reverse"></i>

    <span>
        Data Layout
    </span>

</a>


<a href="<?php echo e(route('admin.hari-libur.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.hari-libur.*') ? 'active' : ''); ?>">

    <i class="bi bi-calendar2-week"></i>

    <span>
        Hari Libur
    </span>

</a>


<a href="<?php echo e(route('admin.users.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">

    <i class="bi bi-people"></i>

    <span>
        Data User
    </span>

</a>


<div class="menu-section">
    SISTEM
</div>


<a href="<?php echo e(route('admin.laporan.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.laporan.*') ? 'active' : ''); ?>">

    <i class="bi bi-file-earmark-bar-graph"></i>

    <span>
        Laporan
    </span>

</a>


<a href="<?php echo e(route('admin.audit-log.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.audit-log.*') ? 'active' : ''); ?>">

    <i class="bi bi-journal-text"></i>

    <span>
        Audit Log
    </span>

</a><?php /**PATH D:\Bank Indo\silakan\resources\views/components/sidebar/admin.blade.php ENDPATH**/ ?>