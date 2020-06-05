<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    public function test_guests_cannot_create_projects()
    {
        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();
        
        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path(). '/edit')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    public function test_guests_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('login');
    
    }

    public function test_guests_cannot_view_a_single_project()
    {
        $project = factory('App\Project')->create(); 

        $this->get($project->path())->assertRedirect('login');
    
    }

    /**@test */
    public function test_a_user_can_create_a_project ()
    {
        
        $this->signIn();

        $this->get('projects/create')->assertStatus(200);

        $this->followingRedirects()
        ->post('/projects', $attributes = factory(Project::class)->raw())
        ->assertSee($attributes['title'])
        ->assertSee($attributes['description'])
        ->assertSee($attributes['notes']);

    }

    public function test_a_user_can_see_all_projects_they_have_been_invited_to()
    {
        //given we're sign in
       

        //and we've been invited to a project that was not created by us
        $project = tap(ProjectFactory::create())->invite($this->signIn());

        //when i visit my dashboard
        //i should see the project 
        $this->get('/projects')
             ->assertSee($project->title); 
    }

    public function test_unauthorized_users_cannot_delete_projects()
    {
        // $this->withExceptionHandling();
        $project = ProjectFactory::create();
    
        $this->delete($project->path())
             ->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())
             ->assertStatus(403);
             
        $project->invite($user);

        $this->actingAs($user)->delete($project->path())->assertStatus(403);

    }

    public function test_a_user_can_delete_a_project()
    {
        $this->withExceptionHandling();
        $project = ProjectFactory::create();
    
        $this->actingAs($project->owner) 
             ->delete($project->path())
             ->assertRedirect('/projects');
             
        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    public function test_a_user_can_update_a_project()
    {

        $project = ProjectFactory::create();
    
        $this->actingAs($project->owner) 
             ->patch($project->path(), $attributes = ['title' => 'changed', 'description' => 'changed', 'notes' => 'changed'])
             ->assertRedirect($project->path());

        $this->get($project->path(). '/edit')->assertOk();

        $this->assertDatabaseHas('projects' , $attributes);
    }

    public function test_a_user_can_update_a_projects_general_notes()
    {
        $project = ProjectFactory::create();
    
        $this->actingAs($project->owner) 
             ->patch($project->path(), $attributes = ['notes' => 'changed']);

        $this->assertDatabaseHas('projects' , $attributes);
    }

    public function test_a_user_can_view_their_project()
    {
        // $this->be(factory('App\User')->create());

        // $this->withoutExceptionHandling();
        

        // $project = factory('App\Project')->create(['owner_id' => auth()->id()]);
        
        $project = ProjectFactory::create();
    
        $this->actingAs($project->owner) 
             ->get($project->path())
             ->assertSee($project->title)
             ->assertSee($project->sentence);
    }

    public function test_an_authenticated_user_cannot_view_the_projects_of_others ()
    {
        $this->be(factory('App\User')->create());

        // $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }

    public function test_an_authenticated_user_cannot_update_the_projects_of_others ()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->patch($project->path())->assertStatus(403);
    }

    public function test_a_project_requires_a_title()
    {
        // $this->actingAs(factory('App\User')->create());
        $this->signIn();

        $attributes = factory('App\Project')->raw(['title'=>'']);
        
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }
    
    public function test_a_project_requires_a_description()
    {
        // $this->actingAs(factory('App\User')->create());
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description'=>'']);
        
        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
    
}
