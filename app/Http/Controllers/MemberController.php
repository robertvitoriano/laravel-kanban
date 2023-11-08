<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserToProjectRequest;
use App\Models\Project;
use App\Models\User;
use \Illuminate\Support\Facades\Log;
class MemberController extends Controller
{
    public function store(AddUserToProjectRequest $request, User $user, Project $project)
    {   
        $user_id = $request->input('user_id');
        $project_id = $request->input('project_id');
        $user = User::find($user_id);
        $user->memberships()->attach([$project_id]);
        return response()->json(['message' => $request->input('user_id')], 201);;
    }
}
