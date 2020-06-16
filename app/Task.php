<?php

namespace App;

use App\Providers\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use RecordActivity;

    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = ['completed' => 'boolean'];

    protected static $recordableEvents = ['created', 'deleted'];


    
    public function project(){
        return $this->belongsTo('App\Project');
    }


    public function path(){
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }


    public function complete(){

        $this->recordActivity('completed task');

        $this->update(['completed' => true]);
        
    }


    public function incomplete(){

        $this->recordActivity('incompleted task');

        $this->update(['completed' => false]);
        
    }


    public function activities(){

        return $this->morphMany('App\Activity','subject')->latest();
        
    }
}
