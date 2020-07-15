<?php

namespace Tests\Feature;

use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_add_tasks_to_project()
    {
        $project = factory('App\Project')->create();

        $this->post($project->path().'/tasks')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_project_may_add_tasks()
    {

        // $this->withoutExceptionHandling();

        $this->signIn();

        $project = ProjectFactory::create();

        $this->post($project->path().'/tasks', ['body'=>'test task'])
        ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body'=>'test task']);
    }

    /** @test */
    public function only_the_owner_of_project_may_update_tasks()
    {

        // $this->withoutExceptionHandling();

        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), ['body'=>'task updated'])
        ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body'=>'tast updated']);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {

        // $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->post($project->path().'/tasks', ['body'=>'Test Tasks']);

        $this->get($project->path())->assertSee('Test Tasks');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch(
            $project->tasks->first()->path(),
            [
                'body' => 'changed',
            ]
        );

        $this->assertDatabaseHas(
            'tasks',
            [
                'body'=> 'changed',

            ]
        );
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch(
            $project->tasks->first()->path(),
            [
                'body'      => 'changed',
                'completed' => true,
            ]
        );

        $this->assertDatabaseHas(
            'tasks',
            [
                'body'      => 'changed',
                'completed' => true,

            ]
        );
    }

    /** @test */
    public function a_task_can_be_marked_as_incomplete()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch(
            $project->tasks->first()->path(),
            [
                'body'      => 'changed',
                'completed' => true,
            ]
        );

        $this->patch(
            $project->tasks->first()->path(),
            [
                'body'      => 'changed',
                'completed' => false,
            ]
        );

        $this->assertDatabaseHas(
            'tasks',
            [
                'body'      => 'changed',
                'completed' => false,

            ]
        );
    }

    /** @test */
    public function a_task_require_body()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
