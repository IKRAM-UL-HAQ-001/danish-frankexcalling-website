<?php

namespace App\Http\Controllers;

use App\Models\Rejoin;
use App\Models\DataEntry;
use App\Models\User;
use App\Models\Exchange;
use Illuminate\Http\Request;
Use App\Exports\RejoinExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RejoinController extends Controller
{
    public function exportRejoin(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $user_id = $request->user_id;
        $exchange_id = User::where('id',$user_id)->value('exchange_id');
        return Excel::download(new RejoinExport($exchange_id, (int) $user_id, $start_date, $end_date), 'Rejoin Export.xlsx');
    }
    
    public function index()
    {
        $Users =User::where('exchange_id', '!=', Null)
        ->get();
        $Exchanges = Exchange::all();
        $Rejoins = DataEntry::where('task_name', 'rejoin')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('admin.rejoin.list',compact('Rejoins', 'Users','Exchanges'));
    }

    public function assistantIndex()
    {
        $Users =User::where('exchange_id', '!=', Null)->get();
        $Rejoins =  DataEntry::where('task_name','rejoin')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('assistant.rejoin.list',compact('Rejoins','Users'));
    }

    public function customercareIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $Rejoins = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'rejoin')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('customer_care.rejoin.list',compact('Rejoins'));
    }
    
    public function destroy(Request $request)
    {
        $rejoin = DataEntry::find($request->id);
        if ($rejoin) {
            $rejoin->delete();
            return redirect()->back();
        }
    }
}
