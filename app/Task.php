<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = ['completed' => 'boolean'];


    protected static function boot(){

        parent::boot();

        static::created(function ($task){

            Activity::create([

                'project_id' => $task->project->id,
                'description' => 'created task'
                
            ]);
            
        });


        static::updated(function ($task){

            if( !$task->completed) return;

            Activity::create([
                'project_id' => $task->project->id,
                'description' => 'completed task'
            ]);
            
        });

        
    }

    
    public function project(){
        return $this->belongsTo('App\Project');
    }


    public function path(){
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }


    public function complete(){

        $this->update(['completed' => true]);
        
    }
}
