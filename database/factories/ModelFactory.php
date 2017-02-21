<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    static $verified;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'is_verified' => $verified ?: $verified = true,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Ticket::class, function (Faker\Generator $faker) {
    static $user_id;

    return [
        'title' => $faker->sentence(),
        'user_id' => $user_id ?: $user_id = 0
    ];
});

$factory->define(App\TicketMessage::class, function (Faker\Generator $faker) {
    static $ticket_id;
    static $user_id;
    static $message;

    return [
        'ticket_id' => $ticket_id ?: $ticket_id = 0,
        'user_id' => $user_id ?: $user_id = 0,
        'message' => $message ?: $message = $faker->paragraph()
    ];
});

$factory->define(App\TicketFile::class, function (Faker\Generator $faker) {
    static $name;
    static $user_id;
    static $ticket_id;
    static $ticket_message_id;

    return [
        'name' => $name ?: '',
        'path' => 'somefakefile.jpg',
        'url' => 'somefakefile.jpg',
        'user_id' => $user_id ?: $user_id = 0,
        'ticket_id' => $ticket_id ?: $ticket_id = 0,
        'ticket_message_id' => $ticket_message_id ?: $ticket_message_id = 0,
        'token' => str_random(40)
    ];
});
