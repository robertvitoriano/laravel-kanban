<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\BoardResource;
use Illuminate\Support\Facades\Auth;
class BoardController extends Controller
{
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        $board = Auth::user()->boards()->create($validated);
        $board->boardMembers()->attach([$board->creator_id]);

        return new BoardResource($board);
    }
}
