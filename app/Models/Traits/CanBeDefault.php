<?php

namespace App\Models\Traits;

trait CanBeDefault
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootCanBeDefault()
    {
        static::creating(function ($model) {
            $model->revokeDefaultFromOthers();
        });
    }

    /**
     * Set the "default" attribute on all other models to false.
     *
     * @return void
     */
    private function revokeDefaultFromOthers()
    {
        if (! $this->default) {
            return;
        }

        $this->newQuery()
            ->where('user_id', $this->user->id)
            ->update(['default' => false]);
    }
}
