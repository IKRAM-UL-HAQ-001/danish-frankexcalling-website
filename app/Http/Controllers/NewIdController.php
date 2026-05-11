<?php

namespace App\Http\Controllers;

use App\Models\NewId;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exchange;
Use App\Exports\NewIdExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\DataEntry;
use Carbon\Carbon;

class NewIdController extends Controller
{
    public function exportNewId(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = (int) $request->user_id;
        $exchange_id = User::where('id', $user_id)->value('exchange_id');

        if (!$exchange_id) {
            return response()->json(['error' => 'Exchange ID not found'], 400);
        }
        return Excel::download(new NewIdExport($exchange_id, $user_id, $start_date, $end_date), 'New Id export.xlsx');
    }

    public function index()
    {
        $Users =User::where('exchange_id', '!=', Null)->get();
        $Exchanges = Exchange::all();
        $NewIds =  DataEntry::where('task_name','newid')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('admin.new_id.list',compact('NewIds', 'Users','Exchanges'));
    }
    
    public function assistantIndex()
    {
        $Exchanges = Exchange::all();
        $Users =User::where('exchange_id', '!=', Null)->get();
        $NewIds =  DataEntry::where('task_name','newid')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('assistant.new_id.list',compact('NewIds', 'Users','Exchanges'));
    }
    
    public function exchangeIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $NewIds = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'newid')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('exchange.new_id.list',compact('NewIds'));
    }
    
    public function customercareIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $NewIds = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'newid')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('customer_care.new_id.list',compact('NewIds'));
    }

    public function destroy(Request $request)
    {
        $newId = DataEntry::find($request->id);
        if ($newId) {
            $newId->delete();
            return redirect()->back();
        }
    }
}
