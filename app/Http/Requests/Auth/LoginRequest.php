<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;


class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }



    public function rules(): array
    {
        return [

            'login_input' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
            ],

        ];
    }



    public function authenticate(): void
    {

        $this->ensureIsNotRateLimited();


        $field = filter_var(
            $this->login_input,
            FILTER_VALIDATE_EMAIL
        )
            ? 'email'
            : 'username';



        if (! Auth::attempt(
            [

                $field => $this->login_input,

                'password' => $this->password,

            ],

            $this->boolean('remember')

        )) {


            RateLimiter::hit(
                $this->throttleKey()
            );


            throw ValidationException::withMessages([

                'login_input' => trans('auth.failed'),

            ]);

        }



        RateLimiter::clear(
            $this->throttleKey()
        );

    }




    protected function ensureIsNotRateLimited(): void
    {

        if (
            ! RateLimiter::tooManyAttempts(
                $this->throttleKey(),
                5
            )
        ) {

            return;

        }



        event(
            new Lockout($this)
        );


        $seconds =
            RateLimiter::availableIn(
                $this->throttleKey()
            );



        throw ValidationException::withMessages([

            'login_input' => trans(
                'auth.throttle',
                [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]
            ),

        ]);

    }




    public function throttleKey(): string
    {

        return Str::transliterate(

            Str::lower(
                $this->string('login_input')
            )
            .
            '|'
            .
            $this->ip()

        );

    }

}