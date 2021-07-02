<?php

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
       parent::setUp();

        $this->user = User::factory()->create([
            'first_name' => 'basic',
            'last_name' => 'user',
            'email' => 'basic@user.com',
            'is_admin' => false,
        ]);

        User::factory()->create([
            'first_name' => 'basic',
            'last_name' => 'user2',
            'email' => 'basic2@user.com',
            'is_admin' => false,
        ]);

    }

    public function test_can_view_users()
    {
        $this->actingAs($this->user)
            ->get('/users')
            ->assertInertia(fn ($assert) => $assert
                ->component('Users/Index')
                ->has('users', 2)
                ->has('users.0', fn ($assert) => $assert
                    ->where('id', 1)
                    ->where('first_name', 'basic')
                    ->where('last_name', 'user')
                    ->where('name', 'basic user')
                    ->where('is_admin', false)
                    ->where('email', 'basic@user.com')
                    ->where('photo', null)
                    ->where('deleted_at', null)
                )
                ->has('users.1', fn ($assert) => $assert
                    ->where('id', 2)
                    ->where('first_name', 'basic')
                    ->where('last_name', 'user2')
                    ->where('name', 'basic user2')
                    ->where('is_admin', false)
                    ->where('email', 'basic2@user.com')
                    ->where('photo', null)
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_can_search_for_users()
    {
        $this->actingAs($this->user)
            ->get('/users?search=user')
            ->assertInertia(fn ($assert) => $assert
                ->component('Users/Index')
                ->where('filters.search', 'user')
                ->has('users', 2)
                ->has('users.0', fn ($assert) => $assert
                    ->where('id', 3)
                    ->where('first_name', 'basic')
                    ->where('last_name', 'user')
                    ->where('name', 'basic user')
                    ->where('is_admin', false)
                    ->where('email', 'basic@user.com')
                    ->where('photo', null)
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_cannot_view_deleted_users()
    {
        User::firstWhere('email', 'basic2@user.com')->delete();

        $this->actingAs($this->user)
            ->get('/users')
            ->assertInertia(fn ($assert) => $assert
                ->component('Users/Index')
                ->has('users', 1)
                ->where('users.0.email', 'basic@user.com')
            );
    }

    public function test_can_update_users()
    {
        User::firstWhere('email', 'basic@user.com')->update(['last_name'=>'user2']);

        $this->actingAs($this->user)
            ->get('/users')
            ->assertInertia(fn ($assert) => $assert
                ->component('Users/Index')
                ->has('users', 2)
                ->where('users.0.email', 'basic@user.com')
            );
    }
}
