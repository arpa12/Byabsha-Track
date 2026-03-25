<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $homeRouteName = $user instanceof User ? $user->homeRouteName() : 'dashboard.index';

            return redirect()->route($homeRouteName);
        }

        return redirect()->route('landing.index', ['auth' => 'login']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $homeRouteName = $user instanceof User ? $user->homeRouteName() : 'dashboard.index';

            return redirect()->intended(route($homeRouteName));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials. Please try again.',
        ])->withInput($request->only('email', '_form'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $homeRouteName = $user instanceof User ? $user->homeRouteName() : 'dashboard.index';

            return redirect()->route($homeRouteName);
        }

        return redirect()->route('landing.index', ['auth' => 'register']);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'owner',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->homeRouteName())
            ->with('success', __('auth.register_success'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing.index');
    }
}
