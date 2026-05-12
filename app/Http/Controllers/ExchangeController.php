<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PhoneNumber;
use App\Models\Exchange;
use App\Models\DataEntry;
use Carbon\Carbon;

class ExchangeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Exchanges = Exchange::all();
        return view('admin.exchange.list',compact('Exchanges'));
    }

    public function exchnageUsers(Request $request)
    {
        $Users = User::where('role', 'exchange')->where('exchange_id',$request->id)->get();
        
        return view('admin.exchange.userlist',compact('Users'));
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
            ->whereYear('created_at', $currentYear)
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

        $TotalNoOfCallDaily = PhoneNumber::whereDate('updated_at', $today)
            ->where('exchange_id', $exchangeId)
            ->where('status', 'deactive')
            ->where('user_id', $userId)
            ->count();

        $TotalPhoneNumberMonthly = PhoneNumber::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->count();
        
        $TotalNoOfCallMonthly = PhoneNumber::whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->where('status', 'deactive')
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->count();

        // Decrypt and Sum Amounts
        $decryptAmounts = function ($amounts) {
            return $amounts->sum(fn($amount) => (float) $this->encryptionService->decrypt($amount));
        };

        $TotalAmountDaily = $decryptAmounts(
            DataEntry::whereDate('updated_at', $today)
                ->where('exchange_id', $exchangeId)
                ->where('user_id', $userId)
                ->pluck('amount')
        );

        $TotalAmountMonthly = $decryptAmounts(
            DataEntry::whereMonth('updated_at', $currentMonth)
                ->whereYear('updated_at', $currentYear)
                ->where('exchange_id', $exchangeId)
                ->where('user_id', $userId)
                ->pluck('amount')
        );

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

    public function assistantIndex()
    {
        $Exchanges = Exchange::all();
        return view('assistant.exchange.list',compact('Exchanges'));
    }

    public function store(Request $request)
    {
        {
            // Define validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:exchanges,name',
            ]);
        
            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 422);
            }
        
            try {
                $encryptedExchangeName = $request->input('name');        
        
                // Store the data using Eloquent ORM
                $exchange = new Exchange();
                $exchange->name = $encryptedExchangeName;
                $exchange->save();
        
                return redirect()->back();
            } catch (\Exception $e) {
                return redirect()->back();
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Exchange $exchange)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request)
    {
        $exchange = Exchange::findOrFail($request->id);    
        $request->validate([
            'name' => 'required',
        ]);
        $exchange->name = $request->name;
        $exchange->save();    
        return redirect()->back();
    }

    public function destroy (Request $request)
    {
        $exchange = Exchange::findOrFail($request->id);
        $exchange->delete();
        return redirect()->back();
    }
}
