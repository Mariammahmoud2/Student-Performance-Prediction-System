<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        // 1. تحقق من الإيميل الأول
        $user = User::where('email', $this->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'This email address is not registered in our system.',
            ]);
        }

        // 2. لو الإيميل موجود، تحقق من الباسورد
        if (! Hash::check($this->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'The password you entered is incorrect.',
            ]);
        }

        // 3. كل حاجة تمام، سجل الدخول
        Auth::login($user, $this->boolean('remember'));
    }
}