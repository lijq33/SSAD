<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\SendEmail;
use App\Mail\DataUpdate;
use PDF;

class EmailTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    
    /** @test */
    public function it_can_send_email(){
        \Mail::fake();
        \Mail::to('test@testingemail.com')->send(new DataUpdate());
        \Mail::assertSent(DataUpdate::class, 1);
    }
    
    /** @test */
    public function it_can_send_email_with_attachment(){
        \Mail::fake();
        \Mail::to('test@testingemail.com')->send(new DataUpdate());
        \Mail::assertSent(DataUpdate::class, function($mail) {
            $mail = $mail->build();        
            return count($mail->attachments) > 0;
        });
    }
}
