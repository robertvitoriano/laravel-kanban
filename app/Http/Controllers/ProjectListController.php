<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectListResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectListRequest;
use illuminate\Support\Facades\Auth;
class ProjectListController extends Controller
{
    public function store(StoreProjectListRequest $request)
    {
        $validated = $request->validated();
        $projectList = Auth::user()->projectLists()->create($validated);
        return new ProjectListResource($projectList);
    }
}
