<?php

namespace Tests\Feature\CrisisTest;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Events\CrisisCreated;
use Illuminate\Support\Facades\Event;

use Illuminate\Foundation\Testing\WithFaker;

class NewCrisisTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function it_can_approve_a_reported_crisis()
    {

      
    }
    
    /** @test */
    public function it_can_reject_a_reported_crisis()
    {

      
    }
}