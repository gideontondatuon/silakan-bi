<div class="menu-section">
    UTAMA
</div>


<a href="<?php echo e(route('dashboard')); ?>"
   class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">

    <i class="bi bi-grid-fill"></i>

    <span>
        Dashboard
    </span>

</a>



<a href="<?php echo e(route('admin.approval.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.approval.*') ? 'active' : ''); ?>">

    <i class="bi bi-clock-history"></i>

    <span>
        Waiting List
    </span>

</a>



<a href="<?php echo e(route('kegiatan.berlangsung.index')); ?>"
class="sidebar-link">

<i class="bi bi-play-circle"></i>

Kegiatan Berlangsung

</a>



<a href="<?php echo e(route('kalender.index')); ?>"
   class="<?php echo e(request()->routeIs('kalender.*') ? 'active' : ''); ?>">

    <i class="bi bi-calendar3"></i>

    <span>
        Kalender Ruangan
    </span>

</a>




<div class="menu-section">
    MASTER DATA
</div>



<a href="<?php echo e(route('admin.ruangan.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.ruangan.*') ? 'active' : ''); ?>">

    <i class="bi bi-building"></i>

    <span>
        Data Ruangan
    </span>

</a>



<a href="<?php echo e(route('admin.fasilitas.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.fasilitas.*') ? 'active' : ''); ?>">

    <i class="bi bi-tools"></i>

    <span>
        Data Fasilitas
    </span>

</a>



<a href="<?php echo e(route('admin.layout.index')); ?>"
   class="<?php echo e(request()->routeIs('admin.layout.*') ? 'active' : ''); ?>">

    <i class="bi bi-layout-text-sidebar-reverse"></i>

    <span>
        Data Layout
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
class="sidebar-link">

<i class="bi bi-journal-text"></i>

Audit Log

</a><?php /**PATH D:\Bank Indo\silakan\resources\views/components/sidebar/admin.blade.php ENDPATH**/ ?>