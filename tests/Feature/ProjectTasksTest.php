<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();
        
        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }
    
    public function test_only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();
        
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task']);
        // ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }
    public function test_only_the_owner_of_a_project_may_update_a_task()
    {
        // $this->withExceptionHandling();

        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();
        
        // $project = factory('App\Project')->create();

        // $task = $project->addTask('test task');

        $this->patch($project->tasks[0]->path() , ['body' => 'changed'])
        ->assertStatus(403);
        // ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    public function test_a_project_can_have_tasks()
    {
        // $this->withoutExceptionHandling();
        // $this->signIn();
        //another option creating project
        // option 1
        // $project = factory(Project::class)->create(['owner_id' => auth()->id()]);
        // option 2
        // $project = auth()->user()->projects()->create(
        //     factory(Project::class)->raw()
        // );

        $project = ProjectFactory::create();
        
        $this->actingAs($project->owner)
             ->post($project->path() . '/tasks', ['body' => 'Test task']);
        
        $this->get($project->path())
        ->assertSee('Test task');
    }

    public function test_a_task_can_be_updated()
    {

        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)
        ->create();
    
        $this->signIn();
        
        $this->actingAs($project->owner) 
            ->patch($project->tasks[0]->path(), [
            'body' => 'changed'
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed'
        ]);
    }
    
    public function test_a_task_can_be_completed()
    {

        // $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)
        ->create();
    
        $this->signIn();

        $this->actingAs($project->owner) 
            ->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    public function test_a_task_can_be_mark_as_incomplete()
    {

        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();
        
        $this->signIn();

        $this->actingAs($project->owner) 
            ->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => true
        ]);
        
        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => 'changed',
            'completed' => false
        ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => false
        ]);
    }
    
    public function test_a_task_requires_a_body()
    {
        
        // $this->signIn();
        
        // $project = auth()->user()->projects()->create(
        //     factory(Project::class)->raw()
        // );

        $project = ProjectFactory::create();

        $attributes = factory('App\Task')->raw(['body'=>'']);
        
        $this->actingAs($project->owner) 
             ->post($project->path() . '/tasks', $attributes)
             ->assertSessionHasErrors('body');
    }
}
