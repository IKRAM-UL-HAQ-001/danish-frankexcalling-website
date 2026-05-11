<?php

namespace App\Http\Controllers;

use App\Models\PhoneNumber;
use App\Models\DataEntry;
use App\Models\User;
use App\Models\Exchange;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        $DataSearch = null;
        return view ('admin.search.list',compact('DataSearch'));
    } 
    public function assistantIndex()
    {
         $DataSearch = null;
        return view ('assistant.search.list',compact('DataSearch'));
    } 
    public function exchangeIndex()
    {
        $DataSearch = null;
        return view ('exchange.search.list',compact('DataSearch'));
    } 
    public function customercareIndex()
    {
        $DataSearch = null;
        return view ('customer_care.search.list',compact('DataSearch'));
    } 

    public function searchDataEntry(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string'
        ]);
        $phoneNumber = $request->phone_number;
        $PhoneNumbers = PhoneNumber::where('phone_number', $phoneNumber)->first();
        
        if ($PhoneNumbers) {
            $phone_id = $PhoneNumbers->id;    
            $DataSearch = DataEntry::with('user', 'exchange', 'phone')
            ->where('phone_id', $phone_id)
            ->paginate(10);
        }else{
            $DataSearch = null;
        }
        $currentRoute = $request->route()->getName();
        if (str_contains($currentRoute, 'customer_care')) {
            return view('customer_care.search.list', compact('DataSearch'));
        }
        else if (str_contains($currentRoute, 'admin')) {
            return view('admin.search.list', compact('DataSearch'));
        }
        else if (str_contains($currentRoute, 'assistant')) {
            return view('assistant.search.list', compact('DataSearch'));
        }
        else{
            return view('exchange.search.list', compact('DataSearch'));
        }
    }

    public function deleteDataEntry(Request $request)
    {
        $dataEntry = DataEntry::find($request->id);
        if ($dataEntry) {
            $PhoneId = $dataEntry->phone_id;
            PhoneNumber::destroy($PhoneId);
            $dataEntry->delete();
            return redirect()->route('admin.search.list');
        }
    }
}
