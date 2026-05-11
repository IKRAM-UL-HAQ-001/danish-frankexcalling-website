<?php

namespace App\Http\Controllers;

use App\Models\NoOfCall;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NoOfCallController extends Controller
{
    public function index()
    {
        $NoOfCalls = PhoneNumber::where('status','deactive')->paginate(20);
        return view('admin.no_of_call.list', compact('NoOfCalls'));
    }

    public function assistantIndex()
    {
        $NoOfCalls = PhoneNumber::where('status','!=','deactive')->paginate(20);
        return view('assistant.no_of_call.list', compact('NoOfCalls'));
    }

    public function exchangeIndex()
    {
        $today = Carbon::today();
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $NoOfCalls = PhoneNumber::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('status','=','deactive')
        ->paginate(20);
        return view('exchange.no_of_call.list', compact('NoOfCalls'));
    }

    public function customercareIndex()
    {
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $NoOfCalls = PhoneNumber::where('exchange_id', $exchangeId)
        ->where('user_id', $userId)
        ->where('status','deactive')->paginate(20);
        return view('customer_care.no_of_call.list', compact('NoOfCalls'));
    }

    public function destroy(Request $request)
    {
        $noOfCall = NoOfCall::find($request->id);
        if ($noOfCall) {
            $noOfCall->delete();
            return redirect()->back();        
        }
    }
}
