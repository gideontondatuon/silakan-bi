<aside class="sidebar">

    <div class="sidebar-brand">
        <div class="brand-title">
            SILAKAN
        </div>

        <div class="brand-subtitle">
            Sistem Informasi
            <br>
            Layanan Kantor
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

        <div class="user-info">

            <strong>
                <?php echo e(auth()->user()->name); ?>

            </strong>

            <small>
                <?php echo e(auth()->user()->role->label()); ?>

            </small>

        </div>


        <form method="POST" action="<?php echo e(route('logout')); ?>">

            <?php echo csrf_field(); ?>

            <button type="submit" class="logout-button">
                Logout
            </button>

        </form>

    </div>

</aside><?php /**PATH D:\Bank Indo\silakan\resources\views/components/sidebar.blade.php ENDPATH**/ ?>