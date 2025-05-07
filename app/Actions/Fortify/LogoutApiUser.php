<?php

namespace App\Actions\Fortify;

use App\Models\User;

class LogoutApiUser
{
    /**
     * Deletes all tokens of user
     *
     * @param  User  $user
     */
    public function execute(User $user): void
    {
        $user->tokens()->delete();
    }
}
