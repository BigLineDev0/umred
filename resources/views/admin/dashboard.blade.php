@extends('dashboard')
@section('title', 'Tableau de Admin')
@section('titre', 'Dashboard')
@section('content')
    <div id="content" class="content">
        <ol class="breadcrumb float-xl-right">
            <li class="breadcrumb-item"><a href="javascript:;">Accueil</a></li>
            <li class="breadcrumb-item active">@yield('titre')</li>
        </ol>

        <h1 class="page-header">Tableau de bord </h1>
        <!-- begin row -->
        <div class="row">
            <!-- Laboratoires -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-primary">
                    <div class="stats-icon"><i class="fa fa-flask"></i></div>
                    <div class="stats-info">
                        <h4>Total Laboratoires</h4>
                        <p>{{ $stats['total_laboratories'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="{{ route('admin.laboratoires.index') }}">Voir <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Équipements -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-info">
                    <div class="stats-icon"><i class="fa fa-microscope"></i></div>
                    <div class="stats-info">
                        <h4>Total Équipements</h4>
                        <p>{{ $stats['total_equipments'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="{{ route('admin.equipements.index') }}">Voir <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Réservations en attente -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-warning">
                    <div class="stats-icon"><i class="fa fa-clock"></i></div>
                    <div class="stats-info">
                        <h4>Réservations en attente</h4>
                        <p>{{ $stats['pending_reservations'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="{{ route('admin.reservations.index') }}">Voir <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Maintenances actives -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-danger">
                    <div class="stats-icon"><i class="fa fa-tools"></i></div>
                    <div class="stats-info">
                        <h4>Maintenances en cours</h4>
                        <p>{{ $stats['active_maintenances'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="">Voir <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Administrateurs-->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-blue">
                    <div class="stats-icon"><i class="fa fa-user-shield"></i></div>
                    <div class="stats-info">
                        <h4>Administrateurs</h4>
                        <p>{{ $stats['admins'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="">Voir <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Chercheurs -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-purple">
                    <div class="stats-icon"><i class="fa fa-user-graduate"></i></div>
                    <div class="stats-info">
                        <h4>Chercheurs</h4>
                        <p>{{ $stats['techniciens'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="{{ route('admin.utilisateurs.index') }}">Voir <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Techniciens -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-green">
                    <div class="stats-icon"><i class="fa fa-user-cog"></i></div>
                    <div class="stats-info">
                        <h4>Techniciens</h4>
                        <p>{{ $stats['techniciens'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="{{ route('admin.utilisateurs.index') }}">Voir <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Équipements disponibles -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-success">
                    <div class="stats-icon"><i class="fa fa-microscope"></i></div>
                    <div class="stats-info">
                        <h4>Équipements disponible</h4>
                        <p>{{ $stats['available_equipments'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="">Voir <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Équipements indisponibles -->
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-danger">
                    <div class="stats-icon"><i class="fa fa-microscope"></i></div>
                    <div class="stats-info">
                        <h4>Équipements indisponible</h4>
                        <p>{{ $stats['occupied_equipments'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="">Voir <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Dernières Réservations -->
            <div class="col-md-6">
                <div class="alert alert-info">
                    <h5 class="mb-3">Dernières Réservations</h5>
                    <ul class="list-unstyled mb-0">
                        @foreach ($recent_reservations as $reservation)
                            <li class="mb-1">
                                <strong>{{ $reservation->user->prenom }} {{ $reservation->user->name }}</strong> —
                                {{ $reservation->date }}
                                <span
                                    class="badge badge-{{ $reservation->statut == 'en_attente' ? 'warning' : 'success' }}">{{ $reservation->statut }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- begin col-8 -->
            <div class="col-xl-8">
                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="index-1">
                    <div class="panel-heading">
                        <h4 class="panel-title">Website Analytics (Last 7 Days)</h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                                data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success"
                                data-click="panel-reload"><i class="fa fa-redo"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                                data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger"
                                data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                    </div>
                    <div class="panel-body pr-1">
                        <div id="interactive-chart" class="height-sm"></div>
                    </div>
                </div>
                <!-- end panel -->


            </div>
            <!-- end col-8 -->
            <!-- begin col-4 -->
            <div class="col-xl-4">
                <!-- begin panel -->

                <!-- begin panel -->
                <div class="panel panel-inverse" data-sortable-id="index-10">
                    <div class="panel-heading">
                        <h4 class="panel-title">Calendar</h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                                data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success"
                                data-click="panel-reload"><i class="fa fa-redo"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                                data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger"
                                data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="datepicker-inline" class="datepicker-full-width overflow-y-scroll position-relative">
                            <div></div>
                        </div>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-4 -->
        </div>
        <!-- end row -->
    </div>
@endsection
