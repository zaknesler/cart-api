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
            if (! $model->default) {
                return;
            }

            $model->revokeDefaultFromOthers();
        });

        static::deleted(function ($model) {
            if (! $model->default) {
                return;
            }

            $model->update(['default' => false]);

            $model->markMostRecentModelAsDefault();
        });
    }

    /**
     * Set the "default" attribute on all other models to false.
     *
     * @return void
     */
    private function revokeDefaultFromOthers()
    {
        $this->newQuery()
            ->where('user_id', $this->user->id)
            ->update(['default' => false]);
    }

    /**
     * Make the most recent model as default after deleting a model.
     *
     * @return void
     */
    private function markMostRecentModelAsDefault()
    {
        $model = $this->newQuery()
            ->where('user_id', $this->user->id)
            ->latest('id')
            ->first();

        if ($model) {
            $model->update(['default' => true]);
        }
    }
}
