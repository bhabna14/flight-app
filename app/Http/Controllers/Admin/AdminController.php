<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;


class AdminController extends Controller
{
    //
    public function adminlogin(){
        return view("adminlogin");
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::guard('admins')->attempt($credentials)) {
            $user = Auth::guard('admins')->user();
    
            // Check if the user is active
            if ($user->status == 'active') {
                return redirect()->intended('/admin/dashboard');
            } else {
                // User is not active, logout and redirect back with error message
                Auth::guard('admins')->logout();
                return redirect()->back()->withErrors(['email' => 'Your account is not active. Please contact support.']);
            }
        } else {
            // Authentication failed...
            return redirect()->back()->withErrors(['email' => 'Invalid credentials.']); // Redirect back with error message
        }
    }
    
    public function dashboard()
    {
       
         return view('admin/dashboard');
       
    } 
    public function adminlogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin');
    }
}
