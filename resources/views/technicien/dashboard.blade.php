@extends('dashboard')
@section('title', 'Tableau de Technicien')
@section('titre', 'Dashboard - Maintenance')

@section('content')
<div id="content" class="content">
    <ol class="breadcrumb float-xl-right">
        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
        <li class="breadcrumb-item active">@yield('titre')</li>
    </ol>

    <h1 class="page-header">Tableau de bord Technicien</h1>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-info">
                <div class="stats-icon"><i class="fa fa-tools"></i></div>
                <div class="stats-info">
                    <h4>Total Maintenances</h4>
                    <p>{{ $stats['total_maintenances'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-warning">
                <div class="stats-icon"><i class="fa fa-spinner fa-spin"></i></div>
                <div class="stats-info">
                    <h4>En Cours</h4>
                    <p>{{ $stats['maintenances_en_cours'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-success">
                <div class="stats-icon"><i class="fa fa-check-circle"></i></div>
                <div class="stats-info">
                    <h4>Terminées</h4>
                    <p>{{ $stats['maintenances_terminées'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="widget widget-stats bg-danger">
                <div class="stats-icon"><i class="fa fa-cogs"></i></div>
                <div class="stats-info">
                    <h4>Équipements Maintenances</h4>
                    <p>{{ $stats['equipements_maintenances'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières maintenances -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-secondary">
                <h5 class="mb-3">Dernières Interventions</h5>
                <ul class="list-group">
                    @forelse ($recent_maintenances as $maintenance)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $maintenance->equipement->nom }} — {{ $maintenance->date_prevue->format('d/m/Y') }}
                            <span class="badge badge-{{ $maintenance->statut === 'en_cours' ? 'warning' : 'success' }}">
                                {{ ucfirst($maintenance->statut) }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item">Aucune intervention récente.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
