<?php

namespace App\Http\Controllers\Tuser\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tuser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('tuser.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Tuser::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'skill_category' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $tuser = Tuser::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'skill_category' => $request->skill_category,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($tuser));

        Auth::guard('tuser')->login($tuser);

        return redirect(route('tuser.tuser.dashboard'));
    }
}
