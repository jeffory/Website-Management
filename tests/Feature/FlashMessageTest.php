<?php

namespace tests\Feature;

use App\Facades\Flash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FlashMessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function ensure_flash_messages_show_on_page()
    {
        $this->signIn();
        Flash::set('Hello world');

        $response = $this->get('/client-area/');
        $response->assertSeeText('Hello world');
    }

}