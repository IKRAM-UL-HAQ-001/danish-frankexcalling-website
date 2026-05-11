<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;
use App\Models\PhoneNumber;
use App\Models\NoOfCall;
use App\Models\User;
use App\Models\Exchange;
use App\Models\Customer;
use App\Models\DemoSend;
use App\Models\Complaint;
use App\Models\FollowUp;
use App\Models\ReferId;
use App\Models\Reject;
use App\Models\TotalCall;
use App\Models\TotalAmount;
use App\Models\Walk;
use App\Models\NewId;
use App\Models\DataEntry;
use Carbon\Carbon;
use Auth;
use DB;
use App\Services\EncryptionService;

class DashboardController extends Controller
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }


    public function index()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        $encryptedAmountsDaily = DB::table('data_entries')
            ->whereDate('updated_at', $today)
            ->pluck('amount');
    
        $encryptedAmountsMonthly = DB::table('data_entries')
            ->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->pluck('amount');
    
        $TotalAmountDaily = $encryptedAmountsDaily->sum(fn($amount) => (float) $this->encryptionService->decrypt($amount));
        $TotalAmountMonthly = $encryptedAmountsMonthly->sum(fn($amount) => (float) $this->encryptionService->decrypt($amount));
    
        $TotalExchange = Exchange::count();
        $TotalUser = User::count();
        
        $TotalPhoneNumberDaily = PhoneNumber::whereDate('created_at', $today)->count();
        $TotalNoOfCallDaily = PhoneNumber::whereDate('updated_at', $today)->where('status', 'deactive')->count();
        $TotalRejectDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'reject')->count();
        $TotalWalkDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'walk')->count();
        $TotalComplaintDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'complaint')->count();
        $TotalReferIdDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'referid')->count();
        $TotalDemoSendDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'demosend')->count();
        $TotalFollowUpDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'followup')->count();
        $TotalNewIdDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'newid')->count();
        $TotalRejoinDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'rejoin')->count();
    
        $TotalPhoneNumberMonthly = PhoneNumber::whereMonth('created_at', $currentMonth)->count();
        $TotalNoOfCallMonthly = PhoneNumber::whereMonth('created_at', $currentMonth)->where('status', 'deactive')->count();
        $TotalRejectMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'reject')->count();
        $TotalWalkMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'walk')->count();
        $TotalComplaintMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'complaint')->count();
        $TotalReferIdMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'referid')->count();
        $TotalDemoSendMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'demosend')->count();
        $TotalFollowUpMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'followup')->count();
        $TotalNewIdMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'newid')->count();
        $TotalRejoinMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'rejoin')->count();
    
        $dailyData = [
            ['label' => "Exchanges", 'value' => $TotalExchange, 'icon' => "ni ni-single-02"],
            ['label' => "Assign Number", 'value' => $TotalPhoneNumberDaily, 'icon' => "ni ni-single-02"],
            ['label' => "No Of Call", 'value' => $TotalNoOfCallDaily, 'icon' => "ni ni-single-02"],
            ['label' => "Users", 'value' => $TotalUser, 'icon' => "ni ni-single-02"],
            ['label' => "Reject", 'value' => $TotalRejectDaily, 'icon' => "ni ni-single-02"],
            ['label' => "Walk", 'value' => $TotalWalkDaily, 'icon' => "ni ni-user-run"],
            ['label' => "Complaint", 'value' => $TotalComplaintDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Refer Id", 'value' => $TotalReferIdDaily, 'icon' => "ni ni-badge"],
            ['label' => "Demo Sent", 'value' => $TotalDemoSendDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Follow Up", 'value' => $TotalFollowUpDaily, 'icon' => "ni ni-chat-round"],
            ['label' => "New Id", 'value' => $TotalNewIdDaily, 'icon' => "ni ni-user-run"],
            ['label' => "Rejoin", 'value' => $TotalRejoinDaily, 'icon' => "ni ni-chat-round"],
            ['label' => "Amount", 'value' => $TotalAmountDaily, 'icon' => "ni ni-chat-round"],
        ];
    
        $monthlyData = [
            ['label' => "Total Assign Number", 'value' => $TotalPhoneNumberMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "Total No Of Call", 'value' => $TotalNoOfCallMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "Total Exchanges", 'value' => $TotalExchange, 'icon' => "ni ni-single-02"],
            ['label' => "Total Reject", 'value' => $TotalRejectMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "Total Walk", 'value' => $TotalWalkMonthly, 'icon' => "ni ni-chart-bar-32"],
            ['label' => "Total Complaint", 'value' => $TotalComplaintMonthly, 'icon' => "ni ni-bell-55"],
            ['label' => "Total Refer Id", 'value' => $TotalReferIdMonthly, 'icon' => "ni ni-collection"],
            ['label' => "Total Demo Sent", 'value' => $TotalDemoSendMonthly, 'icon' => "ni ni-bell-55"],
            ['label' => "Total Follow Up", 'value' => $TotalFollowUpMonthly, 'icon' => "ni ni-time-alarm"],
            ['label' => "Total New Id", 'value' => $TotalNewIdMonthly, 'icon' => "ni ni-user-run"],
            ['label' => "Total Rejoin", 'value' => $TotalRejoinMonthly, 'icon' => "ni ni-chat-round"],
            ['label' => "Total Amount", 'value' => $TotalAmountMonthly, 'icon' => "ni ni-support-16"],
        ];
    
        return view('admin.dashboard', compact('dailyData', 'monthlyData'));
    }
    
    public function assistantIndex()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $TotalExchange = Exchange::count();
        $TotalUser = User::count();
        $TotalPhoneNumberDaily = PhoneNumber::whereDate('created_at', $today)->count();
        $TotalNoOfCallDaily = PhoneNumber::whereDate('created_at', $today)->where('status', 'deactive')->count();

        // Daily counts based on task_name
        $TotalRejectDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'reject')->count();
        $TotalWalkDaily = DataEntry::whereDate('created_at', $today)->where('task_name', 'walk')->count();
        $TotalComplaintDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'complaint')->count();
        $TotalReferIdDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'referid')->count();
        $TotalDemoSendDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'demosend')->count();
        $TotalFollowUpDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'followup')->count();
        $TotalNewIdDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'newid')->count();
        $TotalRejoinDaily = DataEntry::whereDate('updated_at', $today)->where('task_name', 'rejoin')->count();

        // Monthly counts based on task_name
        $TotalPhoneNumberMonthly = PhoneNumber::whereMonth('created_at', $currentMonth)->count();
        $TotalNoOfCallMonthly = PhoneNumber::whereMonth('created_at', $currentMonth)->where('status', 'deactive')->count();
        $TotalRejectMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'reject')->count();
        $TotalWalkMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'walk')->count();
        $TotalComplaintMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'complaint')->count();
        $TotalReferIdMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'referid')->count();
        $TotalDemoSendMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'demosend')->count();
        $TotalFollowUpMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'followup')->count();
        $TotalNewIdMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'newid')->count();
        $TotalRejoinMonthly = DataEntry::whereMonth('updated_at', $currentMonth)->where('task_name', 'rejoin')->count();

        // Prepare dashboard data
        $dailyData = [
            ['label' => "Exchanges", 'value' => $TotalExchange, 'icon' => "ni ni-single-02"],
            ['label' => "Assign Number", 'value' => $TotalPhoneNumberDaily, 'icon' => "ni ni-single-02"],
            ['label' => "No Of Call", 'value' => $TotalNoOfCallDaily, 'icon' => "ni ni-single-02"],
            ['label' => "Users", 'value' => $TotalUser, 'icon' => "ni ni-single-02"],
            ['label' => "Reject Daily", 'value' => $TotalRejectDaily, 'icon' => "ni ni-single-02"],
            ['label' => "Today Walk", 'value' => $TotalWalkDaily, 'icon' => "ni ni-user-run"],
            ['label' => "Complaint", 'value' => $TotalComplaintDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Refer Id", 'value' => $TotalReferIdDaily, 'icon' => "ni ni-badge"],
            ['label' => "Demo Sent", 'value' => $TotalDemoSendDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Follow Up", 'value' => $TotalFollowUpDaily, 'icon' => "ni ni-chat-round"],
            ['label' => "New Id", 'value' => $TotalNewIdDaily, 'icon' => "ni ni-user-run"],
            ['label' => "Rejoin", 'value' => $TotalRejoinDaily, 'icon' => "ni ni-user-run"],
        ];

        $monthlyData = [
            ['label' => "Exchanges", 'value' => $TotalExchange, 'icon' => "ni ni-single-02"],
            ['label' => "Assign Number", 'value' => $TotalPhoneNumberMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "No Of Call", 'value' => $TotalNoOfCallMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "Users", 'value' => $TotalUser, 'icon' => "ni ni-single-02"],
            ['label' => "Reject", 'value' => $TotalRejectMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "Walk", 'value' => $TotalWalkMonthly, 'icon' => "ni ni-chart-bar-32"],
            ['label' => "Complaint", 'value' => $TotalComplaintMonthly, 'icon' => "ni ni-bell-55"],
            ['label' => "Refer Id", 'value' => $TotalReferIdMonthly, 'icon' => "ni ni-collection"],
            ['label' => "Demo Sent", 'value' => $TotalDemoSendMonthly, 'icon' => "ni ni-bell-55"],
            ['label' => "Follow Up", 'value' => $TotalFollowUpMonthly, 'icon' => "ni ni-time-alarm"],
            ['label' => "New Id", 'value' => $TotalNewIdMonthly, 'icon' => "ni ni-user-run"],
            ['label' => "Rejoin", 'value' => $TotalRejoinMonthly, 'icon' => "ni ni-user-run"],
        ];

        return view('assistant.dashboard', compact('dailyData', 'monthlyData'));
    }




    public function exchangeIndex()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
    
        // Daily Metrics
        $TotalPhoneNumberDaily = PhoneNumber::whereDate('created_at', $today)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->count();
    
        $TotalNoOfCallDaily = PhoneNumber::whereDate('updated_at', $today)
            ->where('exchange_id', $exchangeId)
            ->where('status', 'deactive')
            ->where('user_id', $userId)
            ->count();
    
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
                SUM(CASE WHEN task_name = 'complaint' THEN 1 ELSE 0 END) as TotalComplaintDaily
            ")->first();
    
        // Monthly Metrics
        $TotalPhoneNumberMonthly = PhoneNumber::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->count();
    
        $TotalNoOfCallMonthly = PhoneNumber::whereMonth('updated_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'deactive')
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->count();
    
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
                SUM(CASE WHEN task_name = 'complaint' THEN 1 ELSE 0 END) as TotalComplaintMonthly
            ")->first();
    

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
    
        // Prepare Daily Data
        $dailyData = [
            ['label' => "Assign Number", 'value' => $TotalPhoneNumberDaily, 'icon' => "ni ni-single-02"],
            ['label' => "No Of Call", 'value' => $TotalNoOfCallDaily, 'icon' => "ni ni-single-02"],
            ['label' => "Demo Sent", 'value' => $dailyCounts->TotalDemoSendDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Follow Ups", 'value' => $dailyCounts->TotalFollowUpDaily, 'icon' => "ni ni-chat-round"],
            ['label' => "Refer IDs", 'value' => $dailyCounts->TotalReferIdDaily, 'icon' => "ni ni-badge"],
            ['label' => "Reject", 'value' => $dailyCounts->TotalRejectDaily, 'icon' => "ni ni-single-02"],
            ['label' => "Walk-Ins", 'value' => $dailyCounts->TotalWalkDaily, 'icon' => "ni ni-user-run"],
            ['label' => "New Id", 'value' => $dailyCounts->TotalNewIdDaily, 'icon' => "ni ni-user-run"],
            ['label' => "Complaint", 'value' => $dailyCounts->TotalComplaintDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Amount", 'value' => $TotalAmountDaily, 'icon' => "ni ni-money-coins"],
        ];
    
        // Prepare Monthly Data
        $monthlyData = [
            ['label' => "Assign Number", 'value' => $TotalPhoneNumberMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "No Of Call", 'value' => $TotalNoOfCallMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "Demo Sent", 'value' => $monthlyCounts->TotalDemoSendMonthly, 'icon' => "ni ni-bell-55"],
            ['label' => "Follow Ups", 'value' => $monthlyCounts->TotalFollowUpMonthly, 'icon' => "ni ni-time-alarm"],
            ['label' => "Refer IDs", 'value' => $monthlyCounts->TotalReferIdMonthly, 'icon' => "ni ni-collection"],
            ['label' => "Reject", 'value' => $monthlyCounts->TotalRejectMonthly, 'icon' => "ni ni-single-02"],
            ['label' => "Walk-Ins", 'value' => $monthlyCounts->TotalWalkMonthly, 'icon' => "ni ni-chart-bar-32"],
            ['label' => "New Id", 'value' => $monthlyCounts->TotalNewIdMonthly, 'icon' => "ni ni-user-run"],
            ['label' => "Complaint", 'value' => $monthlyCounts->TotalComplaintMonthly, 'icon' => "ni ni-email-83"],
            ['label' => "Amount", 'value' => $TotalAmountMonthly, 'icon' => "ni ni-money-coins"],
        ];
    
        return view('exchange.dashboard', compact('dailyData', 'monthlyData'));
    }
    


    public function customercareIndex(Request $request)
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
    
        $exchangeId = session('exchange_id');
        $userId = session('user_id');

        $TotalRejectDaily = DataEntry::whereDate('updated_at', $today)
        ->where('user_id',$userId)
        ->where('task_name', 'reject')->count();
        
        $TotalWalkDaily = DataEntry::whereDate('updated_at', $today)
        ->where('user_id',$userId)
        ->where('task_name', 'walk')->count();
        
        $TotalComplaintDaily = DataEntry::whereDate('updated_at', $today)
        ->where('user_id',$userId)
        ->where('task_name', 'complaint')->count();
        
        $TotalReferIdDaily = DataEntry::whereDate('updated_at', $today)
        ->where('user_id',$userId)
        ->where('task_name', 'referid')->count();
        
        $TotalDemoSendDaily = DataEntry::whereDate('updated_at', $today)
        ->where('user_id',$userId)
        ->where('task_name', 'demosend')->count();
        
        $TotalFollowUpDaily = DataEntry::whereDate('updated_at', $today)
        ->where('user_id',$userId)
        ->where('task_name', 'followup')->count();
        
        $TotalRejoinDaily = DataEntry::whereDate('updated_at', $today)
        ->where('user_id',$userId)
        ->where('task_name', 'rejoin')->count();

        $TotalNewIdDaily = DataEntry::whereDate('updated_at', $today)
        ->where('user_id',$userId)
        ->where('task_name', 'newid')->count();
        
        $TotalRejectMonthly = DataEntry::whereMonth('updated_at', $currentMonth)
        ->where('user_id',$userId)
        ->where('task_name', 'reject')->count();
        
        $TotalWalkMonthly = DataEntry::whereMonth('updated_at', $currentMonth)
        ->where('user_id',$userId)
        ->where('task_name', 'walk')->count();
        
        $TotalComplaintMonthly = DataEntry::whereMonth('updated_at', $currentMonth)
        ->where('user_id',$userId)
        ->where('task_name', 'complaint')->count();
        
        $TotalReferIdMonthly = DataEntry::whereMonth('updated_at', $currentMonth)
        ->where('user_id',$userId)
        ->where('task_name', 'referid')->count();
        
        $TotalDemoSendMonthly = DataEntry::whereMonth('updated_at', $currentMonth)
        ->where('user_id',$userId)
        ->where('task_name', 'demosend')->count();
        
        $TotalFollowUpMonthly = DataEntry::whereMonth('updated_at', $currentMonth)
        ->where('user_id',$userId)
        ->where('task_name', 'followup')->count();
        
        $TotalRejoinMonthly = DataEntry::whereMonth('updated_at', $currentMonth)
        ->where('user_id',$userId)
        ->where('task_name', 'rejoin')->count();
        
        $TotalNewIdMonthly = DataEntry::whereMonth('updated_at', $currentMonth)
        ->where('user_id',$userId)
        ->where('task_name', 'newid')->count();


        // Daily Metrics
        $TotalPhoneNumberDaily = PhoneNumber::whereDate('created_at', $today)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $userId)
            ->count();
        
        $TotalNoOfCallDaily = PhoneNumber::whereDate('created_at', $today)
            ->where('exchange_id', $exchangeId)
            ->where('status', 'deactive')
            ->where('user_id', $userId)
            ->count();
        
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
    
        // Monthly Metrics
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
    
        // Decrypt and sum amounts
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
    
        // Prepare Daily Data
        $dailyData = [
            ['label' => "Assign Number", 'value' => $TotalPhoneNumberDaily, 'icon' => "ni ni-single-02"],
            ['label' => "No Of Call", 'value' => $TotalNoOfCallDaily, 'icon' => "ni ni-single-02"],
            ['label' => "Demo Sent", 'value' => $dailyCounts->TotalDemoSendDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Follow Ups", 'value' => $dailyCounts->TotalFollowUpDaily, 'icon' => "ni ni-chat-round"],
            ['label' => "Refer IDs", 'value' => $dailyCounts->TotalReferIdDaily, 'icon' => "ni ni-badge"],
            ['label' => "Reject", 'value' => $dailyCounts->TotalRejectDaily, 'icon' => "ni ni-single-02"],
            ['label' => "Walk-Ins", 'value' => $dailyCounts->TotalWalkDaily, 'icon' => "ni ni-user-run"],
            ['label' => "New Id", 'value' => $dailyCounts->TotalNewIdDaily, 'icon' => "ni ni-user-run"],
            ['label' => "Complaint", 'value' => $dailyCounts->TotalComplaintDaily, 'icon' => "ni ni-email-83"],
            ['label' => "Rejoin", 'value' => $dailyCounts->TotalRejoinDaily, 'icon' => "ni ni-user-run"],
            ['label' => "Amount", 'value' => $TotalAmountDaily, 'icon' => "ni ni-money-coins"],
        ];
    
        // Prepare Monthly Data
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
    
        return view('customer_care.dashboard', compact('dailyData', 'monthlyData'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminDashboard $adminDashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminDashboard $adminDashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdminDashboard $adminDashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminDashboard $adminDashboard)
    {
        //
    }
}
