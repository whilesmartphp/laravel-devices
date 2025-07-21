<?php

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase;
use Workbench\App\Models\User;

use function Orchestra\Testbench\workbench_path;

#[WithMigration]
class DevicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_user_can_add_a_device()
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->postJson('/api/devices', [
            'name' => 'test',
            'type' => 'mobile',
            'token' => 'qwertyuiopasdfghjklzxcvbnm',
        ]);

        $response->assertStatus(201);
    }

    protected function createUser(array $attributes = []): User
    {
        return User::create(array_merge([
            'email' => Factory::create()->unique()->safeEmail,
            'name' => Factory::create()->unique()->name,
            'password' => Hash::make('password123'),
        ], $attributes));
    }

    public function test_api_user_can_get_their_devices()
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->get('/api/devices');

        $response->assertStatus(200);
    }

    public function test_api_user_can_update_their_device()
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->postJson('/api/devices', [
            'name' => 'test',
            'type' => 'mobile',
            'token' => 'qwertyuiopasdfghjklzxcvbnm',
        ]);

        $response->assertStatus(201);

        $device_id = $response['data']['id'];
        $response = $this->actingAs($user)->putJson('/api/devices/'.$device_id, [
            'name' => 'test',
            'type' => 'mobile',
            'token' => 'mnbvcxzlkjhgfdsapoiuytrewq',
        ]);

        $response->assertStatus(200);
    }

    public function test_api_user_should_update_only_their_device()
    {
        $user1 = $this->createUser();
        $response = $this->actingAs($user1)->postJson('/api/devices', [
            'name' => 'test',
            'type' => 'mobile',
            'token' => 'qwertyuiopasdfghjklzxcvbnm',
        ]);

        $response->assertStatus(201);

        $user2 = $this->createUser();

        $device_id = $response['data']['id'];
        $response = $this->actingAs($user2)->putJson('/api/devices'.$device_id, [
            'name' => 'test',
            'type' => 'mobile',
            'token' => 'mnbvcxzlkjhgfdsapoiuytrewq',
        ]);

        $response->assertStatus(404);
    }

    public function test_api_user_should_delete_only_their_device()
    {
        $user1 = $this->createUser();
        $response = $this->actingAs($user1)->postJson('/api/devices', [
            'name' => 'test',
            'type' => 'mobile',
            'token' => 'qwertyuiopasdfghjklzxcvbnm',
        ]);

        $response->assertStatus(201);

        $user2 = $this->createUser();

        $device_id = $response['data']['id'];
        $response = $this->actingAs($user2)->delete('/api/devices'.$device_id);

        $response->assertStatus(404);
    }

    public function test_api_user_can_delete_a_device()
    {
        $user = $this->createUser();
        $response = $this->actingAs($user)->postJson('/api/devices', [
            'name' => 'test',
            'type' => 'mobile',
            'token' => 'qwertyuiopasdfghjklzxcvbnm',
        ]);

        $response->assertStatus(201);

        $device_id = $response['data']['id'];
        $response = $this->actingAs($user)->delete('/api/devices/'.$device_id);

        $response->assertStatus(200);
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(
            workbench_path('database/migrations')
        );
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            'Whilesmart\LaravelUserDevices\LaravelUserDevicesServiceProvider',
        ];
    }
}
