<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Fortify;

class AttemptToAuthenticateUser
{
    /**
     * Find user in DB, check password and return user if success.
     *
     * @param  array<string, string>  $input
     */
    public function execute(array $input): User
    {
        $usernameColumn = Fortify::username();

        $user = User::firstWhere($usernameColumn, $input[$usernameColumn]);

        if (!$user || !Hash::check($input['password'], $user->password)) {
            throw ValidationException::withMessages([
                $usernameColumn => ['The provided credentials are incorrect'],
            ]);
        }

        return $user;
    }
}
