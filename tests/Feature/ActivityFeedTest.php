<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;

class ActivityFeedTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function create_a_project_records_activity(){

        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activities);

        $this->assertEquals('created project', $project->activities->first()->description);
        
    }


    /** @test */
    function update_a_project_records_activity(){

        $project = ProjectFactory::create();

        $project->update(['title' => 'updated']);

        $this->assertCount(2, $project->activities);

        $this->assertEquals('updated project', $project->activities->last()->description);
        
    }


    /** @test */
    function create_a_task_records_project_activity(){

        $project = ProjectFactory::create();

        // $task = factory('App\Task')->create(['project_id' => $project->id]);
        $project->addTask('some task');
        
        $this->assertCount(2, $project->activities);

        $this->assertEquals('created task', $project->activities->last()->description);
    }


    /** @test */
    function completing_a_task_records_project_activity(){

        // $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAS($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'updated',
                'completed' => true
            ]);

        $this->assertCount(3, $project->activities);

        $this->assertEquals('completed task', $project->activities->last()->description);
    }
    
}
