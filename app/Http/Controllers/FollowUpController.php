<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exchange;
Use App\Exports\FollowUpExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\DataEntry;
use Carbon\Carbon;

class FollowUpController extends Controller
{
    public function exportFollowUp(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;
        $exchange_id = User::where('id',$user_id)->value('exchange_id');
        return Excel::download(new FollowUpExport($exchange_id, (int) $user_id, $start_date, $end_date), 'Follow Up export.xlsx');
    }
    
    public function index()
    {
        $Users =User::where('exchange_id', '!=', Null)
        ->get();
        $Exchanges = Exchange::all();
        $FollowUps = DataEntry::where('task_name','followup')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('admin.follow_up.list',compact('FollowUps', 'Users','Exchanges'));
    }

    public function assistantIndex()
    {
        $Users =User::where('exchange_id', '!=', Null)
        ->get();
        $Exchanges = Exchange::all();
        $FollowUps = DataEntry::where('task_name','followup')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('assistant.follow_up.list',compact('FollowUps', 'Users','Exchanges'));
    }
    
    public function exchangeIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $FollowUps = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'followup')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        // dd($FollowUps);
        return view('exchange.follow_up.list',compact('FollowUps'));
    }

    public function customercareIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $FollowUps = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'followup')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('customer_care.follow_up.list',compact('FollowUps'));
    }

    public function destroy(Request $request)
    {
        $followUp = DataEntry::find($request->id);
        if ($followUp) {
            $followUp->delete();
            return redirect()->back();
        }
    }
}
