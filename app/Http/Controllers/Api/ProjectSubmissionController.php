<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectSubmissionController extends Controller
{
    public function submit(Project $project): JsonResponse
    {
        $user = auth()->user();

        // 🔐 Vérifier propriété
        if ($project->user_id !== $user->id) {
            return response()->json([
                'message' => 'Non autorisé'
            ], 403);
        }

        // 🔎 Vérifier statut
        if ($project->status !== Project::STATUS_DRAFT) {
            return response()->json([
                'message' => 'Seuls les projets en draft peuvent être soumis'
            ], 400);
        }

        // ✅ Soumettre
        $project->submit();

        return response()->json([
            'message' => 'Projet soumis avec succès',
            'project' => $project
        ]);
    }
}

