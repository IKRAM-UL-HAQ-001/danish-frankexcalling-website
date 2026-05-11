<?php

namespace App\Http\Controllers;

use App\Models\Reject;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exchange;
Use App\Exports\RejectExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\DataEntry;
use Carbon\Carbon;

class RejectController extends Controller
{
    public function exportReject(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;
        $exchange_id = User::where('id',$user_id)->value('exchange_id');
        return Excel::download(new RejectExport($exchange_id, (int) $user_id, $start_date, $end_date), 'Reject Export.xlsx');
    }

    public function index()
    {
        $Users =User::where('exchange_id', '!=', Null)
        ->get();
        $Exchanges = Exchange::all();
        $Rejects = DataEntry::where('task_name','reject')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        // $Rejects = DataEntry::where('user_id',29)->paginate(20);
        return view('admin.reject.list',compact('Rejects', 'Users','Exchanges'));
    }

    public function assistantIndex()
    {
        $Users =User::where('exchange_id', '!=', Null)->get();
        $Exchanges = Exchange::all();
        $Rejects = DataEntry::where('task_name','reject')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('assistant.reject.list',compact('Rejects','Users','Exchanges'));
    }
    
    public function exchangeIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $Rejects = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'reject')    
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('exchange.reject.list',compact('Rejects'));
    }

    public function customercareIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $Rejects = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'reject')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('customer_care.reject.list',compact('Rejects'));
    }

    public function destroy(Request $request)
    {
        $reject = DataEntry::find($request->id);
        if ($reject) {
            $reject->delete();
            return redirect()->back();
        }
    }
}
