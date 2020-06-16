<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_has_a_path(){

        $project = factory('App\Project')->create();

        $this->assertEquals('/projects/' . $project->id, $project->path()); // method path() is defined in Project model.

    }

    /** @test */
    public function it_belongs_to_an_owner(){

        $project = factory('App\Project')->create();

        $this->assertInstanceOf('App\User', $project->owner);

    }

    /** @test */
    public function it_can_add_a_task(){

        $project = factory('App\Project')->create();

        $task = $project->addTask('Test tasks');

        $this->assertCount(1, $project->tasks);

        $this->assertTrue($project->tasks->contains($task));
        
    }


    /** @test */
    public function it_can_invite_a_user(){

        $project = factory('App\Project')->create();

        $user = factory('App\User')->create();
        $project->invite($user);

        $this->assertTrue($project->members->contains($user));
        
    }


}
