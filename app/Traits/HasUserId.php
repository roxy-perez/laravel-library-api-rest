<?php

namespace App\Traits;

trait HasUserId
{
    public static function bootHasUserId()
    {
        static::creating(function ($model) {
            $model->user_id = auth()->id();
        });
    }
}
