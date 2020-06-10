<?php

namespace Tests\Feature;

use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    

    /** @test */
    public function guest_cannot_manage_project(){

        $project = factory('App\Project')->create();


        $this->get('/projects')->assertRedirect('/login');

        $this->get('/projects/' . $project->id)->assertRedirect('/login');

        $this->get('/projects/create')->assertRedirect('/login');

        $this->get($project->path() . '/edit')->assertRedirect('/login');

        $this->post('/projects', $project->toArray())->assertRedirect('/login');

    }
    
    
    /** @test */
    public function a_user_can_create_a_project(){

        // $this->withoutExceptionHandling();

        $this->signIn();
        
        $this->get('/projects/create')->assertStatus(200)->assertViewIs('projects.create');
        
        $attributes = factory('App\Project')->raw([
            'owner_id'=>auth()->id(),
            'notes' => 'General notes here.'
            ]);

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }


    /** @test */
    public function a_user_can_update_a_proejct(){

        $this->withoutExceptionHandling();
        
        $project = ProjectFactory::ownedBy($this->signIn())->create();


        $this->get($project->path() . '/edit')->assertStatus(200);
        
        
        $this->patch($project->path(), $attributes = ['title'=>'changed', 'description'=>'changed', 'notes' => 'updated'])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
        
    }


    /** @test */
    public function a_user_can_update_general_note(){

        $project = ProjectFactory::ownedBy($this->signIn())->create();
        
        
        $this->patch($project->path(), $attributes = ['notes' => 'updated']);

        $this->assertDatabaseHas('projects', $attributes);
        
    }


    /** @test */
    public function a_project_require_a_title(){

        $this->signIn();

        $attributes = factory('App\Project')->raw(['title' => '']);
        
        $this->post('/projects',$attributes)->assertSessionHasErrors('title');

    }

    /** @test */
    public function a_project_require_a_description(){

        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects',$attributes)->assertSessionHasErrors('description');

    }

    /** @test */
    public function a_project_require_an_owner(){

        // $this->withoutExceptionHandling();

        $this->signIn();

        $attributes = factory('App\Project')->raw(['owner_id' => auth()->id()]);

        $this->assertArrayHasKey('owner_id', $attributes);
        
        $response = $this->post('/projects', $attributes);

        // dd(Project::where(['owner_id'=>1])->first());

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

    }

    

    /** @test */
    public function a_user_can_view_their_projects(){

        $user = factory('App\User')->create();

        $project = ProjectFactory::ownedBy($this->signIn())->create();
        
        $this->get($project->path())->assertSee($project->title);

    }

    
    /** @test */
    public function authenthicated_user_cannot_view_project_of_others(){

        $this->signIn();

        $project = factory('App\Project')->create();
        
        $this->get($project->path())->assertStatus(403);

    }


    /** @test */
    public function authenthicated_user_cannot_update_project_of_others(){

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->patch($project->path(), ['notes' => 'updated'])->assertStatus(403);
    }
}
