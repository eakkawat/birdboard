<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function none_owners_may_not_invite_users()
    {
        $project = ProjectFactory::create();

        $john = factory('App\User')->create();
        $chris = factory('App\User')->create();

        $invitationAssertion = function () use ($chris, $john, $project) {
            $this->actingAs($chris)
            ->patch(route('projects.invite', $project), [
                'email' => $john->email, ])
            ->assertStatus(403);
        };

        $invitationAssertion();

        $project->invite($chris);

        $invitationAssertion();
    }

    /** @test */
    public function a_project_can_invite_a_user()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $user_a = factory('App\User')->create();

        // a project owner can see invitation section input
        $this->actingAs($project->owner)->get($project->path())->assertSeeText('Invite a User');

        $this->actingAs($project->owner)->patch($project->path().'/invitations', [
            'email' => $user_a->email,
        ]);

        $this->assertTrue($project->members->contains($user_a->id));
    }

    /** @test */
    public function an_invited_user_can_update_project()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $user_a = factory('App\User')->create();

        $project->invite($user_a);

        $task = ['body' => 'foo task'];
        $this->actingAs($user_a)->post($project->path().'/tasks', $task);

        $this->assertDatabaseHas('tasks', $task);
    }

    /** @test */
    public function the_invited_email_address_must_be_associated_with_a_valid_birdboard_account()
    {

        // $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch(route('projects.invite', $project), ['email' => ''])
            ->assertSessionHasErrors([
                'email' => 'Please enter email.',
            ], null, 'invitations');

        $this->actingAs($project->owner)
            ->patch(route('projects.invite', $project), ['email' => 'nonser@example.com'])
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must have a birdboard account.',
            ], null, 'invitations');
    }
}
