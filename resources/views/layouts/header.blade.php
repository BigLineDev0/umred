<div id="header" class="header navbar-default">

    <div class="navbar-header">
        <a href="/" class="navbar-brand">
            <img src="{{ asset('assets/img/logo/UMRED.png') }}" alt="Logo UMRED" style="width: 475px; height:114px; object-fit: cover;"  />
        </a>
        <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>

    <ul class="navbar-nav navbar-right">
        <li class="navbar-form">
            <form action="" method="POST" name="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Rechercher..." />
                    <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </li>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-14">
                <i class="fa fa-bell"></i>
                <span class="label">5</span>
            </a>
            <div class="dropdown-menu media-list dropdown-menu-right">
                <div class="dropdown-header">NOTIFICATIONS (5)</div>
                <a href="javascript:;" class="dropdown-item media">
                    <div class="media-left">
                        <i class="fa fa-bug media-object bg-silver-darker"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">Server Error Reports <i
                                class="fa fa-exclamation-circle text-danger"></i></h6>
                        <div class="text-muted f-s-10">3 minutes ago</div>
                    </div>
                </a>
                <a href="javascript:;" class="dropdown-item media">
                    <div class="media-left">
                        <img src="{{ asset('assets/img/user/user-1.jpg') }}" class="media-object" alt="" />
                        <i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">John Smith</h6>
                        <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                        <div class="text-muted f-s-10">25 minutes ago</div>
                    </div>
                </a>

                <div class="dropdown-footer text-center">
                    <a href="javascript:;">Voir plus</a>
                </div>
            </div>
        </li>
        <li class="dropdown navbar-user">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                @if (auth()->user()->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil"
                        class="rounded-circle img-thumbnail" width="150" height="150"
                        id="photo-preview">
                @else
                     <img src="{{ asset('assets/img/user/user-13.jpg') }}" alt="" />
                @endif
                <span class="d-none d-md-inline">{{ auth()->user()->email }} </span> <b class="caret"></b>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">Profil</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center py-2 px-3 text-danger">
                        <i class="fas fa-sign-out-alt me-3" style="width: 20px;"></i>
                        <span>Se déconnecter</span>
                    </button>
                </form>
            </div>
        </li>
    </ul>
</div>
