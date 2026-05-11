<?php

namespace App\Http\Controllers;

use App\Models\DemoSend;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exchange;
use App\Models\DataEntry;
Use App\Exports\DemoSendExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DemoSendController extends Controller
{
    
    public function exportDemoSend(Request $request)
    {
        // Retrieve input values
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $user_id = $request->input('user_id');
    
        // Get the exchange_id based on user_id
        $exchange_id = User::where('id', $user_id)->value('exchange_id');
    
    
        // Ensure the parameters are passed in the correct order
        return Excel::download(new DemoSendExport($exchange_id, (int) $user_id, $start_date, $end_date), 'Demo_Send_Report.xlsx');
    }
    

    public function index()
    {
        $Users =User::where('exchange_id', '!=', Null)
        ->get();
        $Exchanges = Exchange::all();
        $DemoSends = DataEntry::with('user')
        ->where('task_name','demosend')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view ('admin.demo_send.list',compact('DemoSends', 'Users','Exchanges'));
    }

    public function assistantIndex()
    {
        $Exchanges = Exchange::all();
        $Users =User::where('exchange_id', '!=', Null)
        ->get();
        $DemoSends =  DataEntry::with('user')
        ->where('task_name','demosend')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view ('assistant.demo_send.list',compact('DemoSends', 'Users','Exchanges'));
    }
    
    public function exchangeIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $DemoSends = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'demosend')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('exchange.demo_send.list',compact('DemoSends'));
    }
    
    public function customercareIndex()
    {   
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $DemoSends = DataEntry::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('task_name', 'demosend')
        ->orderBy('updated_at', 'desc')
        ->paginate(20);
        return view('customer_care.demo_send.list',compact('DemoSends'));
    }
    
    public function destroy(Request $request)
    {
        $demoSend = DataEntry::find($request->id);
        if ($demoSend) {
            $demoSend->delete();
            return redirect()->back();
        }
        return redirect()->back();
    }
}
