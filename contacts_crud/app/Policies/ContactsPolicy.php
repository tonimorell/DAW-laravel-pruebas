<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactsPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->role === 'super' || $user->role === 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'user' || $user->role === 'visitor';
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Contact $contacts
     * @return bool
     */
    public function view(User $user, Contact $contacts): bool
    {
        return $user->role === 'user' && $user->id === $contacts->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->role === 'user';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Contact $contacts
     * @return bool
     */
    public function update(User $user, Contact $contacts): bool
    {
        return $user->role === 'user' && $user->id === $contacts->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Contact $contacts
     * @return bool
     */
    public function delete(User $user, Contact $contacts): bool
    {
        return $user->role === 'user' && $user->id === $contacts->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Contact $contacts
     * @return bool
     */
    public function restore(User $user, Contact $contacts): bool
    {
        return $user->role === 'user' && $user->id === $contacts->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Contact $contacts
     * @return bool
     */
    public function forceDelete(User $user, Contact $contacts): bool
    {
        return $user->role === 'admin';
    }
}
