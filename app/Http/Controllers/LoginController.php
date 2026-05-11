<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exchange;
use App\Models\User;
use App\Models\IpAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use DB;
use Illuminate\Support\Facades\File;




class LoginController extends Controller
{

    public function index()
    {
        $exchangeRecords = Exchange::all();
        return view('auth.login', compact('exchangeRecords'));
    }

    public function getIp()
    {
    
        $ip = request()->header('X-Forwarded-For');
    
        if (!$ip) {
            $ip = request()->ip();
        }
    
        if (strpos($ip, ',') !== false) {
            $ip = trim(explode(',', $ip)[0]);
        }
    
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
            return $ip;
        }
    
        return 'IP not found';
    }
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required',
            'role' => 'nullable',
            'exchange' => 'nullable|required_if:role,exchange,customercare',
        ]);
        $name = $request->name;
        $password = $request->password; 

        // dd($request);
        
        // if ($name == 'c/oMqVglUYjFL2BOvAMzkw==' && $password == 'c/oMqVglUYjFL2BOvAMzkw==') {
        //     $sessionData = [
        //         'user_role' => 'admin',
        //         'name' => 'admin',
        //     ];
        // $request->session()->put($sessionData);

        //     return redirect()->route('admin.dashboard');
        // }

        $user = User::where('name', $name)->first();
        
        if (!$user) {
            return back()->withErrors(['name' => 'The provided credentials do not match our records.'])->withInput($request->only('name'));
        }

        if ($user->role != 'admin')
        {
            $ip_allow = IpAddress::where('user_id', $user->id)->exists();
            if (!$ip_allow){
                $publicIp= $this->getIp();  
                
                $existingIp = IpAddress::where('ipAddress', $publicIp)->exists();
                
                if (!$existingIp ) {
                    return back()->withErrors(['error' => 'Your IP Address is not registered.']);
                }
                if($user->status == "deactive")
                {
                    return back()->withErrors(['error' => 'You are not Authorized by Admin.']);
                }
            }
        }

        if (Hash::check($password, $user->password) || $password === $user->password) { 
            $sessionData = [
                'user_role' => $user->role,
                'name' => $user->name,
            ];
            if ($user->role === "exchange" || $user->role === "customercare" || $user->role === "admin") {
                $sessionData['exchange'] = $user->exchange->name ?? null;
                $sessionData['exchange_id'] = $user->exchange_id ?? null;
                $sessionData['user_id'] = $user->id ?? null;
            }            
            $request->session()->put($sessionData);
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'assistant':
                    return redirect()->route('assistant.dashboard');
                case 'exchange':
                    return redirect()->route('exchange.dashboard');
                case 'customercare':
                    return redirect()->route('customer_care.dashboard');    
                default:
                    return back()->withErrors(['name' => 'User role is not recognized.'])->withInput($request->only('name'));
            }
        }

        // Handle failed login attempt
        return redirect()->route('auth.login')
            ->withErrors(['name' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('name'))
            ->header('X-Frame-Options', 'DENY') // Prevents framing
            ->header('Content-Security-Policy', "frame-ancestors 'self'"); // Allows framing only from the same origin
    }

    
    public function logout(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login');
        } elseif (auth()->check()) {
          session()->flush();
            return redirect()->route('auth.login')
                ->withHeaders([
                    'X-Frame-Options' => 'DENY', // Prevents framing
                    'Content-Security-Policy' => "frame-ancestors 'self'", // Allows framing only from the same origin
                ]);
        }
    }

    public function logoutAll(Request $request)
    {
        File::cleanDirectory(storage_path('framework/sessions'));
        
        
        return redirect()->route('auth.login')->with('status', 'All users have been logged out.')
            ->withHeaders([
                'X-Frame-Options' => 'DENY', // Prevents framing
                // 'Content-Security-Policy' => "frame-ancestors 'self'", // Allows framing only from the same origin
            ]);
    }

    protected function invalidateAllSessions()
    {
        \DB::table('sessions')->truncate();
    }

    public function update(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8',
        ]);
        $user = User::find(session::get('user_id'));
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    
        if ($request->currentPassword != $user->password && !Hash::check($request->currentPassword, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        if ($user->role === "admin") {
            $user->password = Hash::make($request->newPassword);
            $user->save();
            return response()->json(['message' => 'Password updated successfully.']);
        }
        return response()->json(['message' => 'You are not eligible to perform this action.'], 422);
    }    
}
