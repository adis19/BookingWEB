<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Поиск пользователя по email или google_id
            $user = User::where('email', $googleUser->email)
                       ->orWhere('google_id', $googleUser->id)
                       ->first();

            if ($user) {
                // Если пользователь существует, обновляем google_id если его нет
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            } else {
                // Создаем нового пользователя
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(24)), // Случайный пароль
                    'role' => 'user', // По умолчанию обычный пользователь
                    'phone' => '', // Пустые значения для остальных полей
                    'address' => '',
                ]);
            }

            // Авторизуем пользователя
            Auth::login($user);

            return redirect()->intended('/'); // Перенаправляем на главную или куда хотел попасть пользователь

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Ошибка авторизации через Google: ' . $e->getMessage());
        }
    }
}
