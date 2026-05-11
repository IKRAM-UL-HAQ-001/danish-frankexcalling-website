<?php

namespace App\Http\Controllers;

use App\Models\AssignNumber;
use App\Models\DataEntry;
use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AssignNumberController extends Controller
{
   
    public function exchangeIndex()
    {
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $PhoneNumbers =PhoneNumber::where('exchange_id',$exchangeId)
        ->where('user_id',$userId)
        ->where('status', 'active')
        ->paginate(20);
        return view('exchange.assign_number.list',compact('PhoneNumbers'));
    }

    public function customercareIndex()
    {
        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $PhoneNumbers =PhoneNumber::where('exchange_id',$exchangeId)
        ->where('user_id',$userId)
        ->where('status', 'active')
        ->paginate(20);
        return view('customer_care.assign_number.list',compact('PhoneNumbers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function search(Request $request)
    {
        return view ("admin.me.my");
    }

    public function searchFromPhoneNumber(Request $request)
    {
        $phoneNumber = $request->encryptedPhone;
        $user = PhoneNumber::where('phone_number', $phoneNumber)->get();
        dd($user);
        if ($user) {
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'not_found',
                'message' => 'User with this phone number not found.'
            ]);
        }
    }

    public function searchFromDataEntry(Request $request)
    {
        $phoneNumber = $request->encryptedPhone;
        $user = DataEntry::where('phone', $phoneNumber)->get();
        dd($user);
        if ($user) {
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => 'not_found',
                'message' => 'User with this phone number not found.'
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(AssignNumber $assignNumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssignNumber $assignNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssignNumber $assignNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssignNumber $assignNumber)
    {
        //
    }
}
