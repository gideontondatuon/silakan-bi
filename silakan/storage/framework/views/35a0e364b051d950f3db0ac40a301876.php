<div class="menu-section">
    MENU
</div>


<a href="<?php echo e(route('dashboard')); ?>"
   class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">

    Dashboard

</a>


<a href="<?php echo e(route('pemesanan.create')); ?>">
    Pemesanan
</a>


<a href="#">
    Kalender Ruangan
</a>


<a href="<?php echo e(route('pemesanan.index')); ?>">
    Riwayat
</a>

<a href="<?php echo e(route('kalender.index')); ?>">

    Kalender Ruangan

</a>


<a href="#">
    Notifikasi
</a>


<a href="#">
    Profil
</a><?php /**PATH D:\Bank Indo\silakan\resources\views/components/sidebar/user.blade.php ENDPATH**/ ?>