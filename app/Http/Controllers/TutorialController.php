<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class TutorialController extends Controller
{
    public function complete(): JsonResponse
    {
        $user = auth()->user();
        $user->update(['tutorial_completed_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function reset(): JsonResponse
    {
        $user = auth()->user();
        $user->update(['tutorial_completed_at' => null]);

        return response()->json(['ok' => true]);
    }
}
