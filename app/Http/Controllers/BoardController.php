<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBoardRequest;
use App\Http\Resources\BoardCollection;
use App\Http\Resources\BoardResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Board;
use App\Http\Requests\CreateBoardMembershipRequest;
use App\Models\User;
use App\Http\Requests\AddUsersToBoardRequest;
use Illuminate\Support\Facades\Storage;

class BoardController extends Controller
{
    public function store(StoreBoardRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('cover')) {
            $folder = 'project_covers/' . $validated['title'];
            $coverPath = $request->file('cover')->store($folder, 's3');
            Storage::disk('s3')->setVisibility($coverPath, 'public');

            $validated['cover'] = Storage::disk('s3')->url($coverPath);
        }

        $board = Auth::user()->boards()->create($validated);
        $board->boardMembers()->attach([$board->creator_id]);

        return new BoardResource($board);
    }

    public function index(Request $request)
    {
        return new BoardCollection(Board::all());
    }

    public function createBoardMembership(CreateBoardMembershipRequest $request)
    {
        $user_id = $request->input('user_id');
        $boardId = $request->input('board_id');
        $user = User::find($user_id);
        $user->boardMemberships()->attach([$boardId]);
        return response()->json(['message' => 'board membership created'], 201);
    }
    public function addUsersToBoard(AddUsersToBoardRequest $request)
    {
        $user_ids = $request->input('user_ids');
        $boardId = $request->input('board_id');

        $board = Board::find($boardId);

        foreach ($user_ids as $user_id) {
            if (!$board->boardMembers()->where('user_id', $user_id)->exists()) {
                $board->boardMembers()->attach($user_id);
            }
        }

        return response()->json(['message' => 'Board memberships created'], 201);
    }
    public function destroy(Request $request, Board $board)
    {
        $board->delete();
        return response()->json(["message" => "deleted with success!"], 200);
    }
    public function show(Request $request, Board $board)
    {
        $board = $board->load([
            'boardMembers',
            'projectLists',
            'projectLists.projects' => fn($query) => $query->orderBy('order'),
            'projectLists.projects.tasks',
            'projectLists.projects.members'
        ]);

        $totalProjectListsCount = $board->projectLists()->count();
        $board->totalProjectListsCount = $totalProjectListsCount;

        return (new BoardResource($board));
    }

    public function getNonMemberUsers(Request $request)
    {
        $boardId = $request->route('board_id');
        $board = Board::find($boardId);
        if($board === null){
            return response()->json(['message' => "board does'nt exist"], 400);
        }

        $nonMemberUsers = User::whereDoesntHave('boardMemberships', function ($query) use ($boardId) {
            $query->where('board_id', $boardId);
        })->where('level', 'user')->get();

        return response()->json(['users' => $nonMemberUsers], 200);
    }
}
