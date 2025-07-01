<div id="sidebar" class="sidebar">
    <div data-scrollbar="true" data-height="100%">
        <ul class="nav">
            <li class="nav-profile">
                <a href="javascript:;" data-toggle="nav-profile">
                    <div class="cover with-shadow"></div>
                    <div class="image">
                        <img src="{{ asset('assets/img/user/user-13.jpg') }}" alt="Photo profil" />
                    </div>
                    <div class="info">
                        <b class="caret pull-right"></b> {{ auth()->user()->prenom }} {{ auth()->user()->name }}
                        <small>{{ ucfirst(auth()->user()->getRoleNames()->first()) }}</small>
                    </div>
                </a>
            </li>
            <li>
                <ul class="nav nav-profile">
                    <li><a href="logout"><i class="fa fa-sign-out-alt"></i> Se décconecter</a></li>
                </ul>
            </li>
        </ul>
        <ul class="nav">

            {{-- 👑 Menu Admin --}}
            @role('admin')

                <li class="has-sub {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-th-large"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <li class="has-sub {{ request()->routeIs('admin.laboratoires.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.laboratoires.index') }}">
                        <i class="fa fa-flask"></i>
                        <span>Gestion des Laboratoires</span>
                    </a>
                </li>

                <li class="has-sub {{ request()->routeIs('admin.equipements.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.equipements.index') }}">
                        <i class="fa fa-microscope"></i>
                        <span>Gestion des Équipements</span>
                    </a>
                </li>

                <li class="has-sub {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reservations.index') }}">
                        <i class="fa fa-calendar-check"></i>
                        <span>Gestion des Réservations</span>
                    </a>
                </li>

                <li class="has-sub {{ request()->routeIs('admin.utilisateurs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.utilisateurs.index') }}">
                        <i class="fa fa-users"></i>
                        <span>Gestion des Utilisateurs</span>
                    </a>
                </li>

            @endrole

            {{-- 🛠️ Menu Technicien --}}
            @role('technicien')
                <li class="has-sub {{ request()->routeIs('technicien.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('technicien.dashboard') }}">
                        <i class="fa fa-th-large"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <li class="has-sub">
                    <a href="#">
                        <i class="fa fa-tools"></i>
                        <span>Gestion des maintenances</span>
                    </a>
                </li>

                <li class="has-sub">
                    <a href="#">
                        <i class="fa fa-history"></i>
                        <span>Historique maintenances</span>
                    </a>
                </li>

                <li class="has-sub">
                    <a href="#">
                        <i class="fa fa-microscope"></i>
                        <span>Équipements</span>
                    </a>
                </li>
            @endrole

            {{-- 🧪 Menu Chercheur --}}
            @role('chercheur')
                <li class="has-sub {{ request()->routeIs('chercheur.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('chercheur.dashboard') }}">
                        <i class="fa fa-th-large"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <li class="has-sub">
                    <a href="#">
                        <i class="fa fa-calendar-plus"></i>
                        <span>Réserver un laboratoire</span>
                    </a>
                </li>

                <li class="has-sub {{ request()->routeIs('chercheur.reservations.historique') ? 'active' : '' }}">
                    <a href="{{ route('chercheur.reservations.historique') }}">
                        <i class="fa fa-history"></i>
                        <span>Historique des réservations</span>
                    </a>
                </li>

                <li class="has-sub {{ request()->routeIs('chercheur.equipements.disponibles') ? 'active' : '' }}">
                    <a href="{{ route('chercheur.equipements.disponibles') }}">
                        <i class="fa fa-microscope"></i>
                        <span>Équipements disponibles</span>
                    </a>
                </li>

            @endrole

            <li class="has-sub">
                <a href="#">
                    <i class="fa fa-user-circle"></i>
                    <span>Profil</span>
                </a>
            </li>

            {{-- 🔓 Déconnexion --}}
            <li class="has-sub">
                <a href="#">
                    <i class="fa fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </li>

            {{-- Minimiser le menu --}}
            <li>
                <a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify">
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </li>
        </ul>

    </div>
</div>
<div class="sidebar-bg"></div>
