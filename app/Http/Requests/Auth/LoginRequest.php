<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // UBAH: Ganti 'email' menjadi 'username'
            // Hapus validasi 'email' (format email) karena username bisa saja bukan email
            'username' => ['required', 'string'], 
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     * * CATATAN: Method ini mungkin tidak terpakai jika Anda menggunakan
     * logika login manual di Controller (seperti jawaban sebelumnya),
     * tapi tetap kita update agar konsisten.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // UBAH: Gunakan 'username'
        if (! Auth::attempt($this->only('username', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'), // Error message key diganti ke username
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            // UBAH: Error message key diganti ke username
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        // UBAH: Rate limiter key menggunakan 'username'
        return Str::transliterate(Str::lower($this->input('username')).'|'.$this->ip());
    }
}