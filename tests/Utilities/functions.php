<?php
/**
 * Wrapper for factory create.
 *
 * @param $class
 * @param array $attributes
 * @param integer|null $times
 *
 * @return mixed
 */
function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

/**
 * Wrapper for factory make.
 *
 * @param $class
 * @param array $attributes
 * @param integer|null $times
 *
 * @return mixed
 */
function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}

/**
 * Read in a fake HTTP response file.
 *
 * @param $name
 *
 * @return string
 */
function fake_http_response($name)
{
    $responses_directory = base_path('tests/HTTP_Response');

    return file_get_contents($responses_directory. DIRECTORY_SEPARATOR. "{$name}.json");

}