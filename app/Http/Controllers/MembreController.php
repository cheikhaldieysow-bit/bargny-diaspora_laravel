<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MembreController extends Controller
{
    //
    public function membreConnected(Request $request): JsonResponse
    {
        // 1. Récupérer l'utilisateur connecté
        $user = $request->user();

        // 2. Vérifier si l'utilisateur connecté est membre 
        if (!$user->hasRole('membre')) {
            return response()->json(['message' => 'Accès non autorisé. Réservé aux membres.'], 403);
        }


        // 3. Retourner la réponse formatée
        return response()->json([
            'success' => true,
            'Membre' => $user,
        ], 200);
    }
}
