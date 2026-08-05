<aside class="sidebar" id="sidebar">

    
    <div class="sidebar-brand">
        <img src="<?php echo e(asset('images/logo-bi2.png')); ?>"
             class="sidebar-logo"
             alt="Bank Indonesia">

        <div class="sidebar-brand-text">
            <strong>SILAKAN</strong>
            <span>Sistem Informasi Layanan Kantor</span>
            <small>KPwBI Prov. Sulut</small>
        </div>
    </div>

    
    <nav class="sidebar-menu">

        <?php if(auth()->user()->role->value === 'admin'): ?>
            <?php echo $__env->make('components.sidebar.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php else: ?>
            <?php echo $__env->make('components.sidebar.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

    </nav>

    <div class="sidebar-footer">

        <a href="<?php echo e(route('profile.edit')); ?>" class="user-info-link" title="Lihat Profil">
            <div class="user-info">
                <div class="user-avatar-initials" style="<?php echo e(auth()->user()->avatar_style); ?>">
                    <?php echo e(auth()->user()->initials); ?>

                </div>
                <div class="user-info-text">
                    <strong><?php echo e(auth()->user()->name); ?></strong>
                    <small><?php echo e(auth()->user()->role->label()); ?></small>
                </div>
            </div>
        </a>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="logout-button">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </button>
        </form>

    </div>

</aside><?php /**PATH D:\Bank Indo\silakan\resources\views/components/sidebar.blade.php ENDPATH**/ ?>