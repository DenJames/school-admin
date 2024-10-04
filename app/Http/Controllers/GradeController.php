<?php

namespace App\Http\Controllers;

use App\Data\GradeData;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GradeController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Grades/Index', [
            'grades' => GradeData::collect(Auth::user()->grades->load(['team', 'teacher.user'])),
        ]);
    }
}
