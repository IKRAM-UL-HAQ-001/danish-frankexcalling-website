<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\User;
use App\Models\DataEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Carbon\Carbon;
use App\Models\Complaint;
use App\Models\FollowUp;
use App\Models\ReferId;
use App\Models\IpAddress;
use App\Models\Reject;
use App\Models\DemoSend;
use App\Models\NewId;

class UserController extends Controller
{
    public function index()
    {
        $Exchanges = Exchange::all();
        $Users = User::whereNotIn('role', ['admin', 'IPAddress'])
            ->with('exchange', 'ipAddress') // Use 'ipAddress' instead of 'ipAddresse'
            ->get();

        return view('admin.user.list', compact('Exchanges', 'Users'));
    }

    public function assistantIndex()
    {
        $Users = User::whereNotIn('role', ['admin', 'assistant'])
            ->with('exchange')
            ->get();
        return view('assistant.user.list',compact('Users'));
    }

    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required',
            'email' => 'required',
            'exchange_id' => 'nullable',
        ]);

        
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        try {
            // Get encrypted inputs
            $Name = $request->input('user_name');
            $Email = $request->input('email');
            $Password = $request->input('password');
            $ExchangeId = $request->input('exchange_id');
            
            // Store the data using Eloquent ORM
            $user = new User();
            $user->name = $Name;
            $user->email = $Email;
            $user->password = Hash::make($Password);
            $user->status = 'deactive';
            $user->exchange_id = $ExchangeId;
            $user->role = 'exchange';
            $user->save();
            $user->save();

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function ip_allow(Request $request)
    {
        $userId = (int) $request->input('userId');
    
        $ip = new IpAddress();
    
        $ip->user_id = $userId;
        $ip->status = 'active';
    
        $ip->save();
    
        return redirect()->back();
    }
    
    public function userStatus(Request $request)
    {
        $user = User::findOrFail($request->userId);

        $user->status = $request->status;
        $user->save();

        return redirect()->back();
    }
    
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id);
        
        // Prevent admin from deleting themselves if needed (optional but good)
        if ($user->id == session('user_id')) {
             return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->back();
    }

    public function userPerformance(Request $request)
    {
        $userId = $request->id;
        $lastMonthStart = Carbon::now()->startOfMonth();
        $lastMonthEnd = Carbon::now()->endOfMonth();

        $complaintsCount = DataEntry::where('user_id', $userId)->where('task_name', 'complaint')
            ->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $followUpsCount = DataEntry::where('user_id', $userId)->where('task_name', 'followup')
            ->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $referIdsCount = DataEntry::where('user_id', $userId)->where('task_name', 'referid')
            ->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $rejectsCount = DataEntry::where('user_id', $userId)->where('task_name', 'reject')
            ->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $demoSendsCount = DataEntry::where('user_id', $userId)->where('task_name', 'demosend')
            ->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $newIdsCount = DataEntry::where('user_id', $userId)->where('task_name', 'newid')
            ->whereBetween('updated_at', [$lastMonthStart, $lastMonthEnd])
            ->count();
        $data = [
            'user_id' => $userId,
            'complaints' => $complaintsCount,
            'followUps' => $followUpsCount,
            'referIds' => $referIdsCount,
            'rejects' => $rejectsCount,
            'demoSends' => $demoSendsCount,
            'newIds' => $newIdsCount,
        ];

        return response()->json($data, 200);
    }
    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);    
        $request->validate([
            'name' => 'required',
            'email' => 'nullable',
            'password' => 'nullable', 
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); 
        }
        $user->save();    
        return redirect()->back();
        
    }

    public function getUsers(Request $request)
    {
        $request->validate([
            'exchange_id' => 'required|exists:exchanges,id',
        ]);

        $users = User::where('exchange_id', $request->exchange_id)->get();

        return response()->json(['users' => $users]);
    }
}
