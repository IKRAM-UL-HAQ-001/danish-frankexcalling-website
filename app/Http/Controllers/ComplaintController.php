<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Models\Exchange;
use App\Models\DataEntry;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
Use App\Exports\ComplaintExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ComplaintController extends Controller
{

    public function exportComplaint(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;
        $exchange_id = User::where('id',$user_id)->value('exchange_id');
        
        return Excel::download(new ComplaintExport($exchange_id, (int) $user_id, $start_date, $end_date), 'complaints_report.xlsx');
    }

    public function index()
    {
        $Users = User::whereNotNull('exchange_id')->get();
        $Exchanges = Exchange::all();

        $Complaints = DataEntry::where('task_name', 'complaint')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('admin.complaint.list', compact('Complaints', 'Users','Exchanges'));
    }

    public function assistantIndex()
    {
        $Users = User::whereNotNull('exchange_id')->get();
        $Exchanges = Exchange::all();
        $Complaints = DataEntry::where('task_name', 'complaint')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('assistant.complaint.list',compact('Complaints', 'Users','Exchanges'));
    }
    
    public function exchangeIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $Complaints = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'complaint')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('exchange.complaint.list',compact('Complaints'));
    }

    public function customercareIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');

        $Complaints = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'complaint')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('customer_care.complaint.list',compact('Complaints'));
    }
    
    public function destroy(Request $request)
    {
        $complaint = DataEntry::find($request->id);
        if ($complaint) {
            $complaint->delete();
            return redirect()->back();
        }
        return redirect()->back();
    }
}
