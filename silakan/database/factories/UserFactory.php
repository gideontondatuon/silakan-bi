<?php

namespace Database\Factories;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserFactory extends Factory
{

    protected static ?string $password = null;


    public function definition(): array
    {
        return [

            'name' => fake()->name(),

            'username' =>
                fake()
                ->unique()
                ->userName(),


            'email' =>
                fake()
                ->unique()
                ->safeEmail(),


            'password' =>
                static::$password ??=
                Hash::make('password'),


            'role' =>
                Role::USER->value,


            'nama_unit' =>
                fake()->company(),


            'kode_unit' =>
                strtoupper(
                    fake()->lexify('UNIT??')
                ),


            'remember_token' =>
                Str::random(10),

        ];
    }

}