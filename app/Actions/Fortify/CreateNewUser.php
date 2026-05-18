<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'phone' => ['required', 'string', 'max:20'],
            'id_number' => ['required', 'string', 'max:20', 'unique:customers,id_number'],
            'license_number' => ['required', 'string', 'max:50', 'unique:customers,license_number'],
            'license_expiry' => ['required', 'date', 'after:today'],
            'date_of_birth' => ['required', 'date', 'before:-18 years'],
            'address' => ['required', 'string', 'max:500'],
        ])->validate();

        return DB::transaction(function () use ($input): User {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
                'phone' => $input['phone'],
            ]);

            $user->assignRole('customer');

            Customer::create([
                'user_id' => $user->id,
                'full_name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'id_number' => $input['id_number'],
                'license_number' => $input['license_number'],
                'license_expiry' => $input['license_expiry'],
                'date_of_birth' => $input['date_of_birth'],
                'address' => $input['address'],
            ]);

            return $user;
        });
    }
}
