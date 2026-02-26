<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\ProjectEnterpriseSubmitRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectSubmitController extends Controller
{

    public function submit(ProjectEnterpriseSubmitRequest $request, Project $project)
    {
        $this->authorize('submit', $project);

        $project->markAsSubmitted();
        $project->refresh();

        return response()->json([
            'message' => 'Le projet a été soumis avec succès !',
            'project' => $project,
        ], 200);
    }
public function update(UpdateProjectRequest $request, $projectId)
{
    $project = Project::findOrFail($projectId);
    $this->authorize('update', $project); // ✅ objet Project
    
    $project->update($request->validated());
    $project->refresh();

    return response()->json([
        'message' => 'Le projet a été mis à jour avec succès !',
        'project' => $project,
    ], 200);
}


}

