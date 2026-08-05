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


@if($notifications->count() > 0)

<span class="notification-count">

{{ $notifications->count() }}

</span>

@endif


</button>



<div class="notification-panel"
     id="notificationPanel">


<div class="notification-header">

    Notifikasi

</div>



@if($notifications->count())


@foreach($notifications as $notification)


<a href="{{ route(
        'notification.read',
        $notification->id
    ) }}"
   class="notification-item">

<div class="notification-icon">

<i class="bi bi-calendar-event"></i>

</div>



<div class="notification-content">


<strong>

{{ $notification->data['judul'] }}

</strong>


<p>

{{ $notification->data['pesan'] }}

</p>


<small>

{{ $notification->data['waktu'] }}

</small>


</div>


</a>


@endforeach



@else


<div class="notification-empty">

<i class="bi bi-bell-slash"></i>

<p>
Tidak ada notifikasi.
</p>

</div>


@endif


</div>


</div>


        <div class="profile">



            <div class="profile-avatar">

                <i class="bi bi-person-fill"></i>

            </div>





            <div class="profile-info">


                <strong>

                    {{ auth()->user()->name }}

                </strong>



                <small>

                    {{ auth()->user()->role->label() }}

                </small>


            </div>






            <div class="profile-menu">



                <form method="POST"
                      action="{{ route('logout') }}">

                    @csrf



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

</script>