<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Autorise la suppression d'un projet uniquement si :
     * - l'utilisateur est le propriétaire du projet ou un administrateur ;
     * - et que le projet n'a pas encore été financé.
     */
    public function delete(User $user, Project $project): Response
    {
        // Vérifie si l'utilisateur connecté est le propriétaire du projet
        $isOwner = $project->user_id === $user->id;

        // Vérifie si l'utilisateur connecté possède le rôle administrateur
        $isAdmin = $user->isAdmin();

        // Si l'utilisateur n'est ni propriétaire ni administrateur => accès refusé
        if (! ($isOwner || $isAdmin)) {
            return Response::deny("Vous n'avez pas le droit de supprimer un projet qui ne vous appartient pas.
                                    pour le faire il faut être un administrateur");
        }

        // Si le projet est déjà financé => suppression interdite
        if (! $project->canBeDeleted()) {
            return Response::deny('Impossible de supprimer un projet déjà financé.');
        }

        // Toutes les conditions sont respectées => alors suppression autorisée
        return Response::allow();
    }
}
