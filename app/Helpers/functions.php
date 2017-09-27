<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Check if you are a specific route.
 *
 * @return mixed
 */
function route_match($pattern)
{
    return Str::is($pattern, Route::currentRouteName());
}

/**
 * Check if you are a specific route.
 *
 * @return mixed
 */
function route_regex_match($pattern)
{
    return preg_match($pattern, Route::currentRouteName());
}