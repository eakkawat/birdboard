<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationRequest;
use App\Project;
use App\User;

class ProjectInvitationController extends Controller
{
    public function invite(InvitationRequest $request,Project $project){

        $project->invite(User::where('email', $request->email)->first());

        return redirect()->back();
        
    }
}
