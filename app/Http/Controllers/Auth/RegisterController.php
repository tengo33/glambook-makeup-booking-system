<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\TwoFactorService  $twoFactorService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request, TwoFactorService $twoFactorService)
    {
        // Validate registration data with name fields
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user but don't log them in yet
        $user = $this->create($request->all());

        // Store user info in session for 2FA verification
        Session::put('2fa_user_id', $user->id);
        Session::put('2fa_email', $user->email);
        Session::put('2fa_registration', true); // Mark as registration flow

        // Generate and send 2FA code
        $twoFactorService->generateAndSendCode($user);

        // Redirect to 2FA verification page
        return redirect()->route('2fa.show')
            ->with('status', 'Registration successful! Please check your email for the 6-digit verification code.')
            ->with('email', $user->email);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Combine name fields
        $name = trim($data['first_name'] . ' ' . 
                    ($data['middle_name'] ? $data['middle_name'] . ' ' : '') . 
                    $data['last_name'] . 
                    ($data['suffix'] ? ' ' . $data['suffix'] : ''));

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'suffix' => $data['suffix'] ?? null,
            'name' => $name,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'artist', // Default role for new users
            'email_verified_at' => null, // Will be verified after 2FA
            'two_factor_code' => null, // Will be set by TwoFactorService
            'two_factor_expires_at' => null,
            'is_active' => true, // Make sure user is active
        ]);
    }

    /**
     * Check if registration is successful (for AJAX or testing)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkRegistration(Request $request)
    {
        if (!Session::has('2fa_user_id')) {
            return response()->json(['registered' => false]);
        }

        $userId = Session::get('2fa_user_id');
        $user = User::find($userId);

        return response()->json([
            'registered' => $user !== null,
            'email' => $user ? $user->email : null
        ]);
    }
}