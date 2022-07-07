<?php

namespace Tests;

use App\Role;
use App\User;
use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JMac\Testing\Traits\AdditionalAssertions;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, AdditionalAssertions;

    /** enable once after migration */
    use RefreshDatabase;

        
    /** 
     * @var string plural lowercase kebab-case
     * @example (agents|item-formats); admin.(agents|item-formats).index for Agent|ItemFormat model
     */
    static $route_particle;

    /**
     * 
     * @var string plural lowercase snake_case
     * usually equal to self::$route_particle
     * differs on multi-word models
     */
    static $view_particle;

    public function seed_permissions(): void{
        $seeder = new \Database\Seeders\DatabaseSeeder;
        $seeder->call(\Database\Seeders\RoleSeed::class);
        $seeder->call(\Database\Seeders\UserSeed::class);
    }

    /**
     * sign-in user
     * @example $user = $this->login_user();
     * @example $user = $this->login_user('Manager');
     */
    public function login_user( string $title = 'User', array $definition=[]): \App\User{
        $user = $this->create_user($title, $definition);
        $this->actingAs($user);
        return $user;
    }

    /**
     * create user
     * @example $user = $this->create_user('Manager');
     */
    public function create_user( string $role_title = 'User', array $definition = []): \App\User{
        
        $this->seed_permissions();
        
        if($definition){
            $user = \App\User::factory()->create($definition);
        }
        
        else{
            $user = \App\User::factory()->create();
        }
        
        $user->role()->associate(\App\Role::where('title', $role_title)->first());
        
        return $user;
    }

    public function seed_default_data():void{
        // Artisan::call('db:seed --class=SubscriptionDefaultTableSeeder');
        // Artisan::call('db:seed --class=CountriesDefaultTableSeeder');
        // Artisan::call('db:seed --class=GroupsDefaultTableSeeder');
        // Artisan::call('db:seed --class=MemberTypesDefaultTableSeeder');
        // Artisan::call('db:seed --class=EmploymentDefaultTableSeeder');
        // Artisan::call('db:seed --class=EducationDefaultTableSeeder');
        // Artisan::call('db:seed --class=EventDefaultTableSeeder');
        // Artisan::call('db:seed --class=PaperTypeDefaultDataSeeder');
        // Artisan::call('db:seed --class=TopicRealDataSeeder');
        // Artisan::call('db:seed --class=SessionTypeDefaultDataSeeder');
        
    }

    /** 
     * custom message on response status
     * @usage in loops
     */
    public function assertResponseStatus($response, $expectedHttpCode, $message = "Response status mismatch"){
        $this->assertEquals($expectedHttpCode, $response->getStatusCode(), $message);
    }
    
    /** 
     * custom message on response status
     * @usage in loops
     */
    public function assertRouteHas($route, $message = "Response status mismatch"){
        $this->assertEquals($route, $response->getStatusCode(), $message);
    }
                
}
