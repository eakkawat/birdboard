<?php

namespace Tests\Unit;

use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        $user = factory('App\User')->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    public function a_user_has_accessible_projects()
    {
        $john = factory('App\User')->create();

        $project = ProjectFactory::ownedBy($john)->create();

        $this->assertCount(1, $john->accessibleProjects());

        $sally = factory('App\User')->create();

        $project_sally = ProjectFactory::ownedBy($sally)->create();

        $nick = factory('App\User')->create();

        $project_sally->invite($nick);

        $this->assertCount(1, $john->accessibleProjects());

        $project_sally->invite($john);

        $this->assertCount(2, $john->accessibleProjects());
    }
}
