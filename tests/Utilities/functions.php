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
