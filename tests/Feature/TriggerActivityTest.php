<?php

namespace Tests\Feature;

use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activities);

        tap($project->activities->last(), function ($activity) {
            $this->assertEquals('created project', $activity->description);
            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    public function update_a_project()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $origin_title = $project->title;

        $project->update(['title' => 'updated']);

        $this->assertCount(2, $project->activities);

        tap($project->activities->last(), function ($activity) use ($origin_title) {
            $this->assertEquals('updated project', $activity->description);

            $expected = [
                'before' => ['title' => $origin_title],
                'after'  => ['title' => 'updated'],
            ];

            $this->assertEquals($expected, $activity->changes);
        });
    }

    /** @test */
    public function create_a_task()
    {
        $project = ProjectFactory::create();

        // $task = factory('App\Task')->create(['project_id' => $project->id]);
        $project->addTask('some task');

        $this->assertCount(2, $project->activities);

        tap($project->activities->last(), function ($activity) {
            $this->assertEquals('created task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('some task', $activity->subject->body);
        });
    }

    /** @test */
    public function completing_a_task()
    {

        // $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAS($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body'      => 'updated',
                'completed' => true,
            ]);

        $this->assertCount(3, $project->activities);

        $project = $project->refresh();

        tap($project->activities->last(), function ($activity) {
            $this->assertEquals('completed task', $activity->description);

            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    public function incompleting_a_task()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), ['body'=>'updated', 'completed'=>true]);
        $this->patch($project->tasks->first()->path(), ['body'=>'updated', 'completed'=>false]);

        $project = $project->refresh();
        $this->assertCount(4, $project->activities);

        tap($project->activities->last(), function ($activity) {
            $this->assertEquals('incompleted task', $activity->description);

            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    public function delete_a_task()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        // $this->delete($project->tasks->first()->path());
        $project->tasks->first()->delete();

        $this->assertCount(3, $project->fresh()->activities);

        $this->assertEquals('deleted task', $project->fresh()->activities->last()->description);
    }
}
