<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectListResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectListRequest;
use illuminate\Support\Facades\Auth;
use App\Models\ProjectList;

class ProjectListController extends Controller
{
    public function store(StoreProjectListRequest $request)
    {
        $validated = $request->validated();
        Auth::user()->projectLists()->create($validated);
        return response()->json([
            'message' => 'list successfully created',
        ], 201);
    }
    public function destroy(Request $request, ProjectList $projectList)
    {
        $projectList->delete();
        return response()->noContent();
    }
}
