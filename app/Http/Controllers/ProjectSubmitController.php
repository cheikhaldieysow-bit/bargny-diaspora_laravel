<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\ProjectEnterpriseSubmitRequest;

class ProjectController extends Controller
{

    public function submit(ProjectEnterpriseSubmitRequest $request, Project $project)
    {
        $this->authorize('submit', $project);


        $project->markAsSubmitted();

        return redirect()->back()->with('success', 'Le projet a été soumis avec succès !');
    }
}

