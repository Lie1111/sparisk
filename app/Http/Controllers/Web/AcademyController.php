<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academy;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcademyController extends Controller
{
    public function index(Request $request)
    {
        $academies = Academy::query()
            ->withCount('members')
            ->with('owner')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('academies/index', [
            'academies' => $academies,
        ]);
    }

    public function show(Academy $academy)
    {
        $academy->load([
            'owner',
            'members' => function ($q) {
                $q->with('patient')->where('active', true);
            },
        ]);

        return Inertia::render('academies/show', [
            'academy' => $academy,
        ]);
    }
}
