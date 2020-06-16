<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function it_belongs_to_a_project(){

        $task = factory('App\Task')->create();

        $this->assertInstanceOf('App\Project', $task->project);
        
    }
    

    /** @test */
    public function it_has_a_path(){

        $task = factory('App\Task')->create();

        $this->assertEquals('/projects/'.$task->project->id.'/tasks/'.$task->id, $task->path());
        
    }


    /** @test */
    public function it_can_be_complete(){

        $task = factory('App\Task')->create();

        $this->assertFalse($task->completed);
        
        $task->complete();

        $this->assertTrue($task->fresh()->completed);
        
    }


    /** @test */
    public function a_task_can_be_marked_as_incomplete(){

        $task = factory('App\Task')->create();

        $task->complete();

        $task->incomplete();

        $this->assertFalse($task->fresh()->completed);
        
    }
}
