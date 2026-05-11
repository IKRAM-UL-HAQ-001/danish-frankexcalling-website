<?php

namespace App\Http\Controllers;

use App\Models\Walk;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exchange;
use App\Models\DataEntry;
Use App\Exports\WalkExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class WalkController extends Controller
{

    public function exportWalk(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;
        $exchange_id = User::where('id',$user_id)->value('exchange_id');
        return Excel::download(new WalkExport($exchange_id, (int) $user_id, $start_date, $end_date), 'Walk Export.xlsx');
    }

    public function index()
    {
        $Users =User::where('exchange_id', '!=', Null)->get();
        $Exchanges = Exchange::all();
        $Walks =  DataEntry::where('task_name','walk')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('admin.walk.list',compact('Walks', 'Users','Exchanges'));
    }
    
    public function assistantIndex()
    {
        $Users =User::where('exchange_id', '!=', Null)->get();
        $Exchanges = Exchange::all();
        $Walks =  DataEntry::where('task_name','walk')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('assistant.walk.list',compact('Walks','Users','Exchanges'));
    }

    public function exchangeIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $Walks = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'walk')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('exchange.walk.list',compact('Walks'));
    }
    
    public function customercareIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $Walks = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'walk')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('customer_care.walk.list',compact('Walks'));
    }
    
    public function destroy(Request $request)
    {
        $walk = DataEntry::find($request->id);
        if ($walk) {
            $walk->delete();
            return redirect()->back();
        }
    }
}
