<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function destroy(Project $project): JsonResponse
    {
        /* Appelle la Policy pour vérifier
         si l'utilisateur est autorisé à supprimer ce projet */
        $this->authorize('delete', $project);

        // Suppression du projet.
        // Grâce au ON DELETE CASCADE en base de données,
        // tous les documents liés à ce projet seront supprimés automatiquement.
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Projet supprimé avec succès.',
        ], 200);
    }
}
