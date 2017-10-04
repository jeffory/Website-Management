<?php

namespace Tests\Unit;


use App\Facades\Flash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FlashMessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_can_set_a_flash_message()
    {
        $this->assertFalse(Flash::get());

        Flash::set('Hello world');

        $this->assertEquals('Hello world', Flash::get()['message']);
        $this->assertEquals('info', Flash::get()['level']);
    }

}
