<?php

namespace App;

use App\Providers\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordActivity;

    protected $guarded = [];

    public static $recordableEvents = ['created', 'updated'];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function addTasks($tasks)
    {
        return $this->tasks()->createMany($tasks);
    }

    public function activities()
    {
        return $this->hasMany('App\Activity')->latest();
    }

    public function invite($user)
    {
        $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany('App\User', 'project_members')->withTimestamps();
    }
}
