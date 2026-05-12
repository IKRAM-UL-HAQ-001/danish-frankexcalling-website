<?php

namespace App\Http\Controllers;

use App\Models\QuaterlyReport;
use App\Models\Complaint;
use App\Models\FollowUp;
use App\Models\ReferId;
use App\Models\Reject;
use App\Models\DemoSend;
use App\Models\NewId;
use App\Models\User;
use App\Models\DataEntry;
use App\Models\Exchange;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuaterlyReportExport;
use Illuminate\Support\Facades\DB;


class QuaterlyReportController extends Controller
{

    public function exportQuaterlyReport(Request $request)
    {
        return Excel::download(new QuaterlyReportExport(), 'Quarterly Report Excel.xlsx');
    }

    function decryptData($encryptedData) 
    {
        return $encryptedData; 
    }

    public function index()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $fourMonthsAgo = Carbon::now()->subMonths(4);
    
        // Fetch Users and Exchanges
        $Users = User::where('exchange_id', '!=', null)->get();
        $Exchanges = Exchange::all();
    
        // Prepare an array to hold the report data
        $reportDatas = [];
    
        foreach ($Users as $user) {
            // Initialize the total amount for the current user
            $TotalAmountFourMonths = 0;
    
            // Total amount for the last 4 months (demo, refer, reject, new id, follow up)
            $tasks = ['newid'];
            foreach ($tasks as $task) {
                $dataEntries = DataEntry::where('created_at', '>=', $fourMonthsAgo)
                    ->where('exchange_id', $user->exchange_id)
                    ->where('user_id', $user->id)
                    ->where('task_name', $task)
                    ->pluck('amount');
    
                $TotalAmountFourMonths += $dataEntries->sum(fn($amount) => (float) $this->decryptData($amount));
            }
    
            $TotalNewIdCount = DataEntry::where('created_at', '>=', $fourMonthsAgo)
                ->where('exchange_id', $user->exchange_id)
                ->where('user_id', $user->id)
                ->where('task_name', 'newid')
                ->count();
    
            // Prepare the report data for the current user
            $reportDatas[] = [
                'user_name' => $user->name,
                'exchange_name' => $user->exchange->name,
                'TotalNewIdCount' => $TotalNewIdCount,
                'TotalAmountFourMonths' => $TotalAmountFourMonths,
            ];
        }
    
        // Convert the array into a collection and paginate
        $currentPage = request()->input('page', 1); // Current page number
        $perPage = 10; // Number of items per page
        $paginatedReportDatas = collect($reportDatas)->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $pagination = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedReportDatas,
            count($reportDatas),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    
        return view('admin.quaterly_report.list', ['reportDatas' => $pagination]);
    }
    

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(QuaterlyReport $QuaterlyReport)
    {
        //
    }

    public function edit(QuaterlyReport $QuaterlyReport)
    {
        //
    }

    public function update(Request $request, QuaterlyReport $QuaterlyReport)
    {
        //
    }
}


