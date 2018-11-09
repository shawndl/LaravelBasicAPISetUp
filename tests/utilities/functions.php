<?php

/**
 * create a new record in the database
 *
 * @param $class
 * @param array $attributes
 * @param null $times
 * @return mixed
 */
function create($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}

/**
 * makes a record but does not add it to the database
 *
 * @param $class
 * @param array $attributes
 * @param null $times
 * @return mixed
 */
function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}