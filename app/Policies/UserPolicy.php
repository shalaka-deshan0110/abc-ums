<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //Return true if a user has permission to create-user
        return $user->permissions()->contains('create-user');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $authenticatedUser
     * @param User $user
     * @return mixed
     */
    public function update(User $authenticatedUser, User $user)
    {
        //Return true if the authenticated user has the same id with specified model/user id
        //or has permission to update-user

        return $authenticatedUser->id === $user->id || $authenticatedUser->permissions()->contains('update-user');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        //Return true if an authenticated user has permission to delete-user
        return $user->permissions()->contains('delete-user');
    }
}
