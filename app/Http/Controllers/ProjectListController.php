<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectListCollection;
use Illuminate\Support\Facades\DB;
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
    public function getProjectListsByBoard(Request $request)
    {
        // Enable query logging
        DB::enableQueryLog();

        $boardId = $request->route('board_id');
        $projectLists = ProjectList::where('board_id', $boardId)->paginate(4);

        // Log the last executed query
        $lastQuery = DB::getQueryLog();
        info('Last Query: ' . last($lastQuery)['query']);

        return new ProjectListCollection($projectLists);
    }
}
