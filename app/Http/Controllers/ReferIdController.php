<?php

namespace App\Http\Controllers;

use App\Models\ReferId;
use Illuminate\Http\Request;
use App\Models\PhoneNumber;
use App\Models\User;
use App\Models\Exchange;
use App\Models\DataEntry;
Use App\Exports\ReferIdExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReferIdController extends Controller
{
    public function exportReferId(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;
        $exchange_id = User::where('id',$user_id)->value('exchange_id');
        return Excel::download(new ReferIdExport($exchange_id, (int) $user_id, $start_date, $end_date), 'Refer Id export.xlsx');
    }

    public function index()
    {
        $Users =User::where('exchange_id', '!=', Null)
        ->get();
        $Exchanges = Exchange::all();
        $ReferIds = DataEntry::where('task_name','referid')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('admin.refer_id.list',compact('ReferIds', 'Users','Exchanges'));
    }
    
    public function assistantIndex()
    {
        $Exchanges = Exchange::all();
        $Users =User::where('exchange_id', '!=', Null)
        ->get();
        $ReferIds = DataEntry::where('task_name','referid')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('assistant.refer_id.list',compact('ReferIds',  'Users','Exchanges'));
    }

    public function exchangeIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $ReferIds = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'referid')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('exchange.refer_id.list',compact('ReferIds'));
    }

    public function customercareIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $ReferIds = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'referid')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('customer_care.refer_id.list',compact('ReferIds'));
    }
    
    public function destroy(Request $request)
    {
        $referId = DataEntry::find($request->id);
        if ($referId) {
            $referId->delete();
            return redirect()->back();
        }
    }
}
