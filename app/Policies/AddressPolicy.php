<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if a user is allowed to see the address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return bool
     */
    public function show(User $user, Address $address)
    {
        return $user->id == $address->user_id;
    }

    /**
     * Determine if a user is allowed to delete a specified address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return bool
     */
    public function delete(User $user, Address $address)
    {
        return $user->id == $address->user_id;
    }
}
