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

        $input = trim($this->input('login_input', ''));
        $password = trim($this->input('password', ''));
        $remember = $this->boolean('remember');

        // Extract username prefix if user typed an email format like username@domain.com
        $usernamePrefix = str_contains($input, '@') ? explode('@', $input)[0] : $input;

        // Search user flexibly by username, email, nama_unit, or name
        $user = \App\Models\User::where('username', $input)
            ->orWhere('email', $input)
            ->orWhere('username', $usernamePrefix)
            ->orWhere('nama_unit', $input)
            ->orWhere('name', $input)
            ->first();

        $authenticated = false;

        if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            Auth::login($user, $remember);
            $authenticated = true;
        } else {
            // Fallback to Auth::attempt for username or email
            $field = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $authenticated = Auth::attempt([$field => $input, 'password' => $password], $remember)
                || Auth::attempt(['username' => $input, 'password' => $password], $remember);
        }

        if (! $authenticated) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login_input' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
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