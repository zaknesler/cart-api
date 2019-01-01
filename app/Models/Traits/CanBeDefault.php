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
            if ($model->default) {
                $model->newQuery()->where('user_id', $model->user->id)->update([
                    'default' => false,
                ]);
            }
        });
    }

    /**
     * Set the value of the default attribute
     *
     * @param string|bool  $value
     */
    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = (($value === 'true' || $value) ? true : false);
    }
}
