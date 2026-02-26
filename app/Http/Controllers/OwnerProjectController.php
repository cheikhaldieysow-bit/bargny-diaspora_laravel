<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OwnerProjectController extends Controller
{
    /**
     * Afficher la liste des projets de l'utilisateur connecté (Owner).
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Récupérer l'utilisateur connecté
        $user = $request->user();

        // 2. Vérifier si l'utilisateur a le rôle 'owner' (Optionnel mais recommandé selon votre demande)
        if (!$user->hasRole('owner')) {
            return response()->json(['message' => 'Accès non autorisé. Réservé aux owners.'], 403);
        }

        // 3. Récupérer les projets de cet utilisateur avec les documents associés
        // On utilise la relation 'projects' définie dans votre modèle User
        $projects = $user->projects()->with('documents')->get();

        // 4. Retourner la réponse formatée
        return response()->json([
            'success' => true,
            'data' => ProjectResource::collection($projects),
        ], 200);
    }

    /**
     * Afficher les détails d'un projet spécifique.
     */
    public function show(Request $request, Project $project): JsonResponse
    {
        $user = $request->user();

        // Sécurité : Vérifier que le projet appartient bien à l'utilisateur connecté
        if ($project->user_id !== $user->id) {
            return response()->json(['message' => 'Ce projet ne vous appartient pas.'], 403);
        }

        // Charger les documents
        $project->load('documents');

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project),
        ], 200);
    }
}