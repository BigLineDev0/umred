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
                        <a href="{{ route('chercheur.reservations.historique') }}">Voir plus <i
                                class="fa fa-arrow-alt-circle-right"></i></a>
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
                        <a href="{{ route('chercheur.reservations.historique') }}">Voir plus <i
                                class="fa fa-arrow-alt-circle-right"></i></a>
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
                        <a href="{{ route('chercheur.reservations.historique') }}">Voir plus <i
                                class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-blue">
                    <div class="stats-icon"><i class="fa fa-vials"></i></div>
                    <div class="stats-info">
                        <h4>Equipements disponibles</h4>
                        <p>{{ $stats['equipements_utilises'] }}</p>
                    </div>
                    <div class="stats-link">
                        <a href="{{ route('chercheur.equipements.disponibles') }}">Voir plus <i
                                class="fa fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Réservations du jour -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Réservations du Jour : {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        @php
                            $today = \Carbon\Carbon::now()->toDateString();
                            $today_reservations = $recent_reservations->filter(function ($reservation) use ($today) {
                                return \Carbon\Carbon::parse($reservation->date)->toDateString() === $today;
                            });
                        @endphp

                        @if ($today_reservations->isEmpty())
                            <div class="p-3">Aucune réservation pour aujourd'hui.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Laboratoire</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($today_reservations as $reservation)
                                            <tr>
                                                <td>{{ $reservation->laboratoire->nom }}</td>
                                                <td>{{ $reservation->date->format('d/m/Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $reservation->statut == 'en_attente' ? 'warning' : 'success' }}">
                                                        {{ ucfirst($reservation->statut) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dernières Réservations -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">5 Dernières Réservations</h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($recent_reservations->isEmpty())
                            <div class="p-3">Aucune réservation récente.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Laboratoire</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recent_reservations as $reservation)
                                            <tr>
                                                <td>{{ $reservation->laboratoire->nom }}</td>
                                                <td>{{ $reservation->date->format('d/m/Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge
                                            @switch($reservation->statut)
                                                @case('en_attente') badge-warning @break
                                                @case('confirmée') badge-success @break
                                                @case('annulée') badge-danger @break
                                                @default badge-secondary
                                            @endswitch">
                                                    {{ ucfirst($reservation->statut) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
