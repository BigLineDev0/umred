@extends('dashboard')
@section('title', 'Tableau de chercheur')
@section('titre', 'Réservations')
@section('content')
<div id="content" class="content">
    <ol class="breadcrumb float-xl-right">
        <li class="breadcrumb-item"><a href="javascript:;">Accueil</a></li>
        <li class="breadcrumb-item active">@yield('titre')</li>
    </ol>

    <h1 class="page-header">Tableau de bord </h1>

    <div class="row">

        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-info">
                <div class="stats-icon"><i class="fa fa-clock"></i></div>
                <div class="stats-info">
                    <h4>En attente</h4>
                    <p>{{ $stats['en_attente'] }}</p>
                </div>
                <div class="stats-link">
                    <a href="javascript:;">Voir plus <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-success">
                <div class="stats-icon"><i class="fa fa-check"></i></div>
                <div class="stats-info">
                    <h4>Confimée</h4>
                    <p>{{ $stats['confirmees'] }}</p>
                </div>
                <div class="stats-link">
                    <a href="javascript:;">Voir plus <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-red">
                <div class="stats-icon"><i class="fa fa-times"></i></div>
                <div class="stats-info">
                    <h4>Annulée</h4>
                    <p>{{ $stats['annulees'] }}</p>
                </div>
                <div class="stats-link">
                    <a href="javascript:;">Voir plus <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-green">
                <div class="stats-icon"><i class="fa fa-check-circle"></i></div>
                <div class="stats-info">
                    <h4>Terminée</h4>
                    <p>{{ $stats['terminees'] }}</p>
                </div>
                <div class="stats-link">
                    <a href="javascript:;">Voir plus <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-dark">
                <div class="stats-icon"><i class="fa fa-vials"></i></div>
                <div class="stats-info">
                    <h4>Equipements disponibles</h4>
                    <p>{{ $stats['equipements_utilises'] }}</p>
                </div>
                <div class="stats-link">
                    <a href="javascript:;">Voir plus <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Dernières Réservations -->
        <div class="col-md-6">
            <div class="alert alert-info">
                <h5 class="mb-3">Dernières Réservations</h5>
                @if ($recent_reservations->isEmpty())
                    <p>Aucune réservation récente.</p>
                @else
                    <ul class="list-unstyled mb-0">
                        @foreach ($recent_reservations as $reservation)
                            <li class="mb-2">
                                <strong>{{ $reservation->laboratoire->nom }}</strong> —
                                {{ $reservation->date->format('d/m/Y') }}
                                <span class="badge
                                    @switch($reservation->statut)
                                        @case('en_attente') badge-warning @break
                                        @case('confirmée') badge-primary @break
                                        @case('terminée') badge-success @break
                                        @case('annulée') badge-danger @break
                                        @default badge-secondary
                                    @endswitch">
                                    {{ ucfirst($reservation->statut) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
