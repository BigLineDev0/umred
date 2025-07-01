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

    public function laboratoires()
    {
        $laboratoires = Laboratoire::withCount('equipements')->latest()->paginate(9);
        return view('pages.labs', compact('laboratoires'));
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
