<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class AdminLoginController extends Controller
    {
        // Show the login form
        public function showLoginForm()
        {
            return view('admin.login');
        }

        // Handle login request
        public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Attempt to log the admin in
            if (Auth::attempt($request->only('email', 'password'))) {
                // Redirect to the intended page after successful login
                return redirect()->route('dashboard');
            }

            // If login fails, redirect back with an error
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // Handle logout request
        public function logout(Request $request)
        {
            Auth::logout();
            return redirect()->route('admin.login');
        }
    }
