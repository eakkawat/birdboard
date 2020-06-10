<?php

namespace App\Observers;

use App\Activity;
use App\Project;

class ProjectObserver
{
    /**
     * Handle the project "created" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
       $this->recordActivty($project, 'created project');
    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function updated(Project $project)
    {
        $this->recordActivty($project, 'updated project');

    
    }

    
    protected function recordActivty(Project $project, $type){

        Activity::create([
            'project_id' => $project->id,
            'description' => $type
    
        ]);
        
    }
}
