<?php
/**
 * Created by PhpStorm.
 * User: shawnlegge
 * Date: 9/4/18
 * Time: 1:32 PM
 */

namespace App\Traits\Models;


use Illuminate\Database\Eloquent\Builder;

trait HasNameTrait
{
    /**
     * when a category is entered into the database the words must be lower case
     *
     * @param $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * when retrieved from the database it must be ucfirst
     *
     * @param $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return $this->attributes['name'] = ucwords($value);
    }

    /**
     * gets query result by name
     *
     * @param Builder $builder
     * @param $name
     * @return Model|Builder
     */
    public function scopeName(Builder $builder, $name)
    {
        return $builder->where('name', strtolower($name))->first();
    }
}