<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    /**
     * Add project.
     *
     * @param Project $project
     *
     * @return back
     */
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate(['body' => 'required']);

        $project->addTask(request()->body);

        return redirect($project->path());
    }

    /**
     * Update project.
     *
     * @param Project $project
     * @param Task    $task
     *
     * @return back
     */
    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);

        $attributes = request()->validate(['body' => 'required']);
        $task->update($attributes);

        $method = request('completed') ? 'complete' : 'incomplete';
        $task->$method();

        return redirect()->back();
    }
}
