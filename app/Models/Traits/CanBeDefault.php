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

        static::deleted(function ($model) {
            $model->update(['default' => false]);

            $model->markAnotherModelAsDefault();
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

    /**
     * Make the first other model default after deleting a model.
     *
     * @return void
     */
    private function markAnotherModelAsDefault()
    {
        $this->newQuery()
            ->where('user_id', $this->user->id)
            ->first()
            ->update(['default' => true]);
    }
}
