<?php

namespace Tests\Feature;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_owners_may_not_invite_users()
    {
        $project = ProjectFactory::create();
        
        $user = factory(User::class)->create();

        $assertInvitationForbidden = function () use ($user, $project) {
            $this->actingAs($user)
                ->post($project->path() . '/invitations')
                ->assertStatus(403);
        };

        $assertInvitationForbidden();

        $project->invite($user);

        $assertInvitationForbidden();
    }

    public function test_a_project_owner_can_invite_a_user()
    {
        $this->withoutExceptionHandling();
        
        $project = ProjectFactory::create();

        $userToInvite = factory(User::class)->create();

        $this->actingAs($project->owner)
             ->post($project->path() . '/invitations', [
                'email' => $userToInvite->email
              ])
             ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    public function test_the_invited_email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $project = ProjectFactory::create();
    
        $this->actingAs($project->owner)
             ->post($project->path(). '/invitations', [
                'email' => 'notauser@example.com'
             ])
             ->assertSessionHasErrors([
                 'email' => 'The user you are inviting must have a Birdboard account.'
             ], null, 'invitations');

    }

    public function test_invited_users_may_update_project_details()
    {
        //Given I have a project
        $project = ProjectFactory::create();

        //And the owner of the projects invites another user
        $project->invite($newUser = factory(User::class)->create());

        //Then, that new user will have permission to add tasks
        $this->signIn($newUser);
        //this is sama dengan ketika kita mengetikkan manual di url
        $this->post(action('ProjectTasksController@store', $project), $task = ['body' => 'Foo task']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
