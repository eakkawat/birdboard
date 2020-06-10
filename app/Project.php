<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PDO;

class Project extends Model
{
    protected $guarded = [];

    public function path(){
        
        return "/projects/{$this->id}";

    }

    public function owner(){

        return $this->belongsTo('App\User');

    }

    public function tasks(){
        return $this->hasMany('App\Task');
    }

    public function addTask($body){
        
        return $this->tasks()->create(compact('body'));

    }
}
