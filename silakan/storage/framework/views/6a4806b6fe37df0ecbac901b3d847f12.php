<header class="navbar">


    <div class="navbar-left">


        <button class="menu-toggle">

            <i class="bi bi-list"></i>

        </button>



        <div>

            <h3>
                SILAKAN
            </h3>


            <span>
                Sistem Informasi Layanan Kantor
            </span>


        </div>


    </div>





    <div class="navbar-right">



<div class="notification dropdown">


<button class="notification-button"
        onclick="toggleNotification()">


<i class="bi bi-bell"></i>


<?php if($notifications->count() > 0): ?>

<span class="notification-count">

<?php echo e($notifications->count()); ?>


</span>

<?php endif; ?>


</button>



<div class="notification-panel"
     id="notificationPanel">


<div class="notification-header">

    Notifikasi

</div>



<?php if($notifications->count()): ?>


<?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


<a href="<?php echo e(route(
        'notification.read',
        $notification->id
    )); ?>"
   class="notification-item">

<div class="notification-icon">

<i class="bi bi-calendar-event"></i>

</div>



<div class="notification-content">


<strong>

<?php echo e($notification->data['judul']); ?>


</strong>


<p>

<?php echo e($notification->data['pesan']); ?>


</p>


<small>

<?php echo e($notification->data['waktu']); ?>


</small>


</div>


</a>


<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<?php else: ?>


<div class="notification-empty">

<i class="bi bi-bell-slash"></i>

<p>
Tidak ada notifikasi.
</p>

</div>


<?php endif; ?>


</div>


</div>


        <div class="profile">



            <div class="profile-avatar">

                <i class="bi bi-person-fill"></i>

            </div>





            <div class="profile-info">


                <strong>

                    <?php echo e(auth()->user()->name); ?>


                </strong>



                <small>

                    <?php echo e(auth()->user()->role->label()); ?>


                </small>


            </div>






            <div class="profile-menu">



                <form method="POST"
                      action="<?php echo e(route('logout')); ?>">

                    <?php echo csrf_field(); ?>



                    <button type="submit">

                        <i class="bi bi-box-arrow-right"></i>

                        Logout


                    </button>


                </form>


            </div>



        </div>



    </div>



</header>

<script>

function toggleNotification()
{

    let panel =
        document.getElementById(
            'notificationPanel'
        );


    panel.classList.toggle(
        'show'
    );

}


document.addEventListener(
'click',
function(event){


let dropdown =
document.querySelector(
'.notification'
);


if(
dropdown &&
!dropdown.contains(event.target)
){

document.getElementById(
'notificationPanel'
)
.classList.remove(
'show'
);

}


});

</script><?php /**PATH D:\Bank Indo\silakan\resources\views/components/navbar.blade.php ENDPATH**/ ?>