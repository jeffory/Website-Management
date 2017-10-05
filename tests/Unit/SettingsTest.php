<?php

namespace Tests\Unit;

use App\Settings;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SettingsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_can_store_multiple_variable_types_and_return_them_correctly()
    {
        Settings::set('boolean_test', true);
        Settings::set('string_test', 'hello world');
        Settings::set('array_test', ['a' => 'hello', 'b' => 'world']);
        Settings::set('integer_test', 6786);
        Settings::set('null_test', null);

        $date = \Carbon\Carbon::now();
        Settings::set('date_test', $date);

        $this->assertSame(true, Settings::get('boolean_test'));
        $this->assertSame('hello world', Settings::get('string_test'));
        $this->assertSame(['a' => 'hello', 'b' => 'world'], Settings::get('array_test'));
        $this->assertSame(6786, Settings::get('integer_test'));
        $this->assertEquals($date, Settings::get('date_test'));
//        $this->assertNull(Settings::get('null_test'));
    }
}
