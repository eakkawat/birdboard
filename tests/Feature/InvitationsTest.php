<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{

    use RefreshDatabase;


    /** @test */
    public function none_owners_may_not_invite_users(){

        $project = ProjectFactory::create();


        $john = factory('App\User')->create();
        $chris = factory('App\User')->create();

        $this->actingAs($chris)
            ->patch(route('projects.invite',$project),[
            'email' => $john->email])
            ->assertStatus(403);

        $project->invite($chris);

        $this->actingAs($chris)
        ->patch(route('projects.invite',$project),[
        'email' => $john->email])
        ->assertStatus(403);

    }


    /** @test */
    public function a_project_can_invite_a_user(){

        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $user_a = factory('App\User')->create();

        $this->actingAs($project->owner)->patch($project->path()."/invitations", [
            'email' => $user_a->email
        ]);

        $this->assertTrue($project->members->contains($user_a->id));

        
    }


    /** @test */
    public function an_invited_user_can_update_project(){

        
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $user_a = factory('App\User')->create();

        $project->invite($user_a);

        $task = ['body' => 'foo task'];
        $this->actingAs($user_a)->post($project->path().'/tasks', $task);

        $this->assertDatabaseHas('tasks', $task);
        
    }


    /** @test */
    public function the_invited_email_address_must_be_associated_with_a_valid_birdboard_account(){

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch(route('projects.invite',$project), ['email' => ''])
            ->assertSessionHasErrors([
                'email' => 'Please enter email.'
            ]);
        
        $this->actingAs($project->owner)
            ->patch(route('projects.invite',$project), ['email' => 'nonser@example.com'])
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must have a birdboard account.'
            ]);

        
        
    }
   
}
