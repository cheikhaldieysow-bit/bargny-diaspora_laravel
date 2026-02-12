<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectEnterpriseSearchRequest;
use App\Http\Resources\ProjectResources;
use App\Services\ProjectSearchServices;
use Illuminate\Http\JsonResponse;

class ProjectSearchController extends Controller
{
    protected ProjectSearchServices $searchService;

    public function __construct(ProjectSearchServices $searchService)
    {
        $this->searchService = $searchService;
    }

    public function search(ProjectEnterpriseSearchRequest $request): JsonResponse
    {
        $user = $request->user();


        // pass the owner's projects relation factory so the service can scope queries
        $result = $this->searchService->search(fn() => $user->projects(), $request->validated());

        $paginator = $result['paginator'];
        $facets = $result['facets'];

        // ProjectResources collection from paginator preserves meta & links
        $resourceResponse = ProjectResources::collection($paginator)->response()->getData(true);

        // merge facets at root level
        $resourceResponse['facets'] = $facets;

        return response()->json($resourceResponse);
    }
}
