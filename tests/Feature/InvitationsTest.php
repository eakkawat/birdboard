<?php

namespace Tests\Feature;

use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_project_can_invite_a_user(){

        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $user_a = factory('App\User')->create();

        $project->invite($user_a);

        $task = ['body' => 'foo task'];
        $this->actingAs($user_a)->post($project->path().'/tasks', $task);

        $this->assertDatabaseHas('tasks', $task);

        
    }
   
}
