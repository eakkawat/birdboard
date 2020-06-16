<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_user(){

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->assertInstanceOf(User::class, $project->activities->first()->user);
        
    } 
    
}
