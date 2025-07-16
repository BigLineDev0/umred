<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\Laboratoire;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $stats = [
            'total_users' => User::count(),
            'total_laboratories' => Laboratoire::count(),
            'total_equipments' => Equipement::count(),
        ];

        $laboratoires = Laboratoire::withCount('equipements')
            ->orderByDesc('equipements_count')
            ->take(3)
            ->get();

        return view('pages.home', compact('stats', 'laboratoires'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function laboratoires(Request $request)
    {
        $query = Laboratoire::withCount('equipements');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('localisation')) {
            $query->where('localisation', $request->localisation);
        }

        $laboratoires = $query->latest()->paginate(9);
        $total = $laboratoires->total();
        $localisations = Laboratoire::select('localisation')->distinct()->pluck('localisation');

        return view('pages.labs', compact('laboratoires', 'total', 'localisations'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
