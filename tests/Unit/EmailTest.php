<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\SendEmail;
use App\Mail\TeamTenReport;
use PDF;

class EmailTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    
    /** @test */
    public function it_can_send_email_to_the_correct_user(){
        \Mail::fake();
        \Mail::to('test@testingemail.coms')->send(new TeamTenReport());
        \Mail::assertSent(TeamTenReport::class, function($mail) {
            return $mail->hasTo('test@testingemail.com');
        });
    }
    
    /** @test */
    public function it_can_send_email_with_attachment(){
        \Mail::fake();
        \Mail::to('test@testingemail.com')->send(new TeamTenReport());
        \Mail::assertSent(TeamTenReport::class, function($mail) {
            $mail = $mail->build();        
            return count($mail->attachments) > 0;
        });
    }
}
