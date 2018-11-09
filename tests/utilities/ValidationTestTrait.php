<?php

/**
 * Created by PhpStorm.
 * User: shawnlegge
 * Date: 20/2/18
 * Time: 11:19 AM
 */
trait ValidationTestTrait
{
    /**
     * returns a validation error
     *
     * @param $name
     * @return string
     */
    public function validationError($name)
    {
        $errors = session('errors');
        return $errors->get($name)[0];
    }
}