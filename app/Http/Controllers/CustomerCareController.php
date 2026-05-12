<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exchange;
use App\Models\PhoneNumber;
use App\Models\DataEntry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CustomerCareController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $Exchanges = Exchange::all();
        // $CustomerCares = User::where('role', 'customercare')
        // ->where('exchange_id', $request->id)
        // ->get();
        return view('admin.customer_care.exchangelist', compact('Exchanges'));
    }

    public function userlist(Request $request)
    {
        $CustomerCares = User::where('role', 'customercare')
        ->where('exchange_id', $request->id)
        ->get();
        return view('admin.customer_care.list', compact('CustomerCares'));
    }

    public function assistantIndex()
    {
        $CustomerCares = User::where('role', 'customercare')->get();
        return view('assistant.customer_care.list', compact('CustomerCares'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required',
            'email' => 'required',
            'exchange' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            // Get encrypted inputs
            $encryptedUserName = $request->input('user_name');
            $encryptedPassword = $request->input('password');
            $encryptedEmail = $request->input('email');
            $encryptedExchange = $request->input('exchange');

            // Store the data using Eloquent ORM
            $user = new User();
            $user->name = $encryptedUserName;
            $user->password = Hash::make($encryptedPassword);
            $user->email = $encryptedEmail;
            $user->exchange_id = $encryptedExchange;
            $user->role = 'customercare';
            $user->status = 'deactive';
            $user->save();

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    


    public function popDashboard(Request $request)
    {
        $userId = $request->id;
        $exchangeId = $request->exchange_id;

        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Grouped Daily Metrics
        $dailyCounts = DataEntry::whereDate('updated_at', $today)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->selectRaw("
                SUM(CASE WHEN task_name = 'demosend' THEN 1 ELSE 0 END) as TotalDemoSendDaily,
                SUM(CASE WHEN task_name = 'followup' THEN 1 ELSE 0 END) as TotalFollowUpDaily,
                SUM(CASE WHEN task_name = 'referid' THEN 1 ELSE 0 END) as TotalReferIdDaily,
                SUM(CASE WHEN task_name = 'reject' THEN 1 ELSE 0 END) as TotalRejectDaily,
                SUM(CASE WHEN task_name = 'walk' THEN 1 ELSE 0 END) as TotalWalkDaily,
                SUM(CASE WHEN task_name = 'newid' THEN 1 ELSE 0 END) as TotalNewIdDaily,
                SUM(CASE WHEN task_name = 'rejoin' THEN 1 ELSE 0 END) as TotalRejoinDaily,
                SUM(CASE WHEN task_name = 'complaint' THEN 1 ELSE 0 END) as TotalComplaintDaily
            ")->first();

        // Grouped Monthly Metrics
        $monthlyCounts = DataEntry::whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->selectRaw("
                SUM(CASE WHEN task_name = 'demosend' THEN 1 ELSE 0 END) as TotalDemoSendMonthly,
                SUM(CASE WHEN task_name = 'followup' THEN 1 ELSE 0 END) as TotalFollowUpMonthly,
                SUM(CASE WHEN task_name = 'referid' THEN 1 ELSE 0 END) as TotalReferIdMonthly,
                SUM(CASE WHEN task_name = 'reject' THEN 1 ELSE 0 END) as TotalRejectMonthly,
                SUM(CASE WHEN task_name = 'walk' THEN 1 ELSE 0 END) as TotalWalkMonthly,
                SUM(CASE WHEN task_name = 'newid' THEN 1 ELSE 0 END) as TotalNewIdMonthly,
                SUM(CASE WHEN task_name = 'rejoin' THEN 1 ELSE 0 END) as TotalRejoinMonthly,
                SUM(CASE WHEN task_name = 'complaint' THEN 1 ELSE 0 END) as TotalComplaintMonthly
            ")->first();
        // Phone Numbers and Call Metrics
        
        $TotalPhoneNumberDaily = PhoneNumber::whereDate('created_at', $today)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->count();

        $TotalNoOfCallDaily = PhoneNumber::whereDate('created_at', $today)
            ->where('exchange_id', $exchangeId)
            ->where('status', 'deactive')
            ->where('user_id', $userId)
            ->count();

    
        $TotalPhoneNumberMonthly = PhoneNumber::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->count();

        
        $TotalNoOfCallMonthly = PhoneNumber::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'deactive')
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->count();

        // Decrypt and Sum Amounts
        
        $encryptedAmountsDaily = DataEntry::whereDate('updated_at', $today)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->pluck('amount');
    
        $encryptedAmountsMonthly = DataEntry::whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->pluck('amount');
    
        $TotalAmountDaily = $encryptedAmountsDaily->sum(fn($amount) => (float) $this->encryptionService->decrypt($amount));
        $TotalAmountMonthly = $encryptedAmountsMonthly->sum(fn($amount) => (float) $this->encryptionService->decrypt($amount));
    

        // Prepare Response Data
        $dailyData = [
            ['label' => "Assign Number", 'value' => $TotalPhoneNumberDaily, 'icon' => "ni ni-single-02"],
            ['label' => "No Of Call", 'value' => $TotalNoOfCallDaily, 'icon' => "ni ni-single-02"],
            ['label' => "Demo Sent", 'value' => $dailyCounts->TotalDemoSendDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Follow Ups", 'value' => $dailyCounts->TotalFollowUpDaily, 'icon' => "ni ni-chat-round"],
            ['label' => "Refer IDs", 'value' => $dailyCounts->TotalReferIdDaily, 'icon' => "ni ni-badge"],
            ['label' => "Reject", 'value' => $dailyCounts->TotalRejectDaily, 'icon' => "ni ni-folder-remove"],
            ['label' => "Walk-Ins", 'value' => $dailyCounts->TotalWalkDaily, 'icon' => "ni ni-user-run"],
            ['label' => "New Id", 'value' => $dailyCounts->TotalNewIdDaily, 'icon' => "ni ni-user-run"],
            ['label' => "Complaint", 'value' => $dailyCounts->TotalComplaintDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Rejoin", 'value' => $dailyCounts->TotalRejoinDaily, 'icon' => "ni ni-user-run"],
            ['label' => "Amount", 'value' => $TotalAmountDaily, 'icon' => "ni ni-money-coins"],
        ];

        $monthlyData = [
            ['label' => "Assign Number", 'value' => $TotalPhoneNumberMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "No Of Call", 'value' => $TotalNoOfCallMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "Demo Sent", 'value' => $monthlyCounts->TotalDemoSendMonthly, 'icon' => "ni ni-bell-55"],
            ['label' => "Follow Ups", 'value' => $monthlyCounts->TotalFollowUpMonthly, 'icon' => "ni ni-time-alarm"],
            ['label' => "Refer IDs", 'value' => $monthlyCounts->TotalReferIdMonthly, 'icon' => "ni ni-collection"],
            ['label' => "Reject", 'value' => $monthlyCounts->TotalRejectMonthly, 'icon' => "ni ni-folder-remove"],
            ['label' => "Walk-Ins", 'value' => $monthlyCounts->TotalWalkMonthly, 'icon' => "ni ni-chart-bar-32"],
            ['label' => "New Id", 'value' => $monthlyCounts->TotalNewIdMonthly, 'icon' => "ni ni-user-run"],
            ['label' => "Complaint", 'value' => $monthlyCounts->TotalComplaintMonthly, 'icon' => "ni ni-email-83"],
            ['label' => "Rejoin", 'value' => $monthlyCounts->TotalRejoinMonthly, 'icon' => "ni ni-user-run"],
            ['label' => "Amount", 'value' => $TotalAmountMonthly, 'icon' => "ni ni-money-coins"],
        ];

        return response()->json([
            'dailyData' => $dailyData,
            'monthlyData' => $monthlyData
        ]);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);    
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8', 
        ]);
        $user->name = $request->name;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); 
        }
        $user->save();    
        return redirect()->route('admin.customer_care.exchangelist');        
    }

    public function destroy(Request $request)
    {
        $customercare = User::find($request->id);
        if ($customercare) {
            $customercare->delete();
            return redirect()->route('admin.customer_care.exchangelist');
        }
        return redirect()->route('admin.customer_care.exchangelist');
    }
}
