<?php
/**
 * Created by PhpStorm.
 * User: shawnlegge
 * Date: 9/4/18
 * Time: 2:51 PM
 */

namespace App\Traits\Models;


use App\User;

trait HasUserTrait
{
    /**
     * the model has a single User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}