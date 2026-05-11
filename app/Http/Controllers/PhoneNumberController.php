<?php

namespace App\Http\Controllers;

use App\Models\PhoneNumber;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\uploadFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
Use App\Exports\PhoneNumbersExport;
use Illuminate\Support\Facades\DB;


class PhoneNumberController extends Controller
{
    public function exportActivePhoneNumbers()
    {
        return Excel::download(new PhoneNumbersExport, 'active_phone_numbers.xlsx');
    }


    public function index()
    {
        $users = User::where('role', '!=', 'admin')->where('role', '!=', 'assistant')->get();
        $PhoneNumbers = PhoneNumber::where('status', 'active')
        // ->where('user_id', 19)
        ->paginate(100);
        $uploadedFiles = uploadFile::all();
        return view('admin.phone_number.list', compact('users', 'PhoneNumbers', 'uploadedFiles'));
    }

    public function assistantIndex()
    {
        $users = User::where('role', '!=', 'admin')->where('role', '!=', 'assistant')->get();
        $PhoneNumbers = PhoneNumber::where('status', 'active')->paginate(20);
        $uploadedFiles = uploadFile::all();
        return view('assistant.phone_number.list', compact('users', 'PhoneNumbers','uploadedFiles'));
    }

    public function custom_delete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No entries selected.'], 400);
        }
        try {
            PhoneNumber::whereIn('id', $ids)->delete();
            return response()->json(['message' => 'Selected entries deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete selected entries.'], 500);
        }
    }

    public function fileStore(Request $request)
    {
        $request->validate([
            'encrypted_file_data' => 'required|json', 
            'user_id' => 'required',
        ]);
        $encryptedNumbers = json_decode($request->input('encrypted_file_data'), true);
        
        if (empty($encryptedNumbers)) {
            return back()->with('error', 'No valid phone numbers received.');
        }
        $phoneNumbers = [];
        $duplicateNumbers = [];
        $exchange_id = User::where('id', (int) $request->user_id)->value('exchange_id');

        
        foreach ($encryptedNumbers as $encryptedPhone) {
            $decryptedPhoneNumber = $encryptedPhone; 

            if (!PhoneNumber::where('phone_number', $decryptedPhoneNumber)->exists()) {
                $phoneNumbers = [
                    'phone_number' => $decryptedPhoneNumber,
                    'user_id' => $request->user_id, 
                    'exchange_id' => $exchange_id, 
                    'status' => 'active', 
                    'created_at' => Carbon::today(),
                    'updated_at' => Carbon::today(),
                ];
                PhoneNumber::insert($phoneNumbers);
            } else {
                $duplicateNumbers[] = $decryptedPhoneNumber;
            }
        }
        
        if (count($duplicateNumbers) > 0) {
            $duplicateCount = count($duplicateNumbers);
            $duplicates = implode(', ', $duplicateNumbers);
            session()->flash('error', "There were {$duplicateCount} duplicate entries that were not added");
        } else {
            session()->flash('success', 'All phone numbers were added successfully.');
        }
    
        return redirect()->route('admin.phone_number.list');
    }



    public function formStore(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone_number' => 'required'
        ]);    
        $encryptedUserId = $request->input('user_id');
        $encryptedPhone = $request->input('phone_number');
        $exchange_id = User::where('id', $encryptedUserId)->value('exchange_id');

        $existingPhoneNumber = PhoneNumber::where('phone_number', $encryptedPhone)->first();
        if ($existingPhoneNumber) {
            session()->flash('error', "The Numbers is already assigned");
            return redirect()->back();
        }

        $phoneNumber = new PhoneNumber();
        $phoneNumber->user_id = $encryptedUserId;
        $phoneNumber->phone_number = $encryptedPhone;
        $phoneNumber->exchange_id = $exchange_id;
        $phoneNumber->status = 'active';
        $phoneNumber->save();
        return redirect()->back();
    }
     




    /**
     * Display the specified resource.
     */

public function run()
{
    $testEntries = DB::table('test_entries')->get();

    foreach ($testEntries as $entry) {
        $exists = DB::table('data_entries')
            ->where('phone_id', $entry->phone_id)
            ->exists();

        if ($exists) {
            DB::table('test_entries')
                ->where('id', $entry->id)
                ->delete();
        } else {
            DB::table('data_entries')->insert([
                'name' => $entry->name,
                'phone_id' => $entry->phone_id,
                'feedback' => $entry->feedback,
                'amount' => $entry->amount,
                'task_name' => $entry->task_name,
                'user_id' => $entry->user_id,
                'exchange_id' => $entry->exchange_id,
                'created_at' => $entry->created_at,
                'updated_at' => $entry->updated_at,
            ]);

            // After successful insertion, delete from test_entries
            DB::table('test_entries')
                ->where('id', $entry->id)
                ->delete();
        }
    }
    return redirect()->back()->with('success', 'Migration completed successfully.');
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhoneNumber $phoneNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PhoneNumber $phoneNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $phoneNumber = PhoneNumber::find($request->id);
        if ($phoneNumber) {
            $phoneNumber->delete();
            return redirect()->back();
        }

        return redirect()->back();
    }
    public function searchPhoneNumber(Request $request)
    {
        $phoneNumber = $request->phone_number;
        $PhoneNumbers = PhoneNumber::with('user')
        ->where('phone_number', $phoneNumber)
        ->paginate(1);

        // Check if the request expects JSON (AJAX call)
        return response()->json([
            'phoneNumbers' => $PhoneNumbers->items(), // Get paginated items
            'pagination' => [
                'total' => $PhoneNumbers->total(),
                'per_page' => $PhoneNumbers->perPage(),
                'current_page' => $PhoneNumbers->currentPage(),
                'last_page' => $PhoneNumbers->lastPage(),
                'next_page_url' => $PhoneNumbers->nextPageUrl(),
                'prev_page_url' => $PhoneNumbers->previousPageUrl(),
            ],
            'success' => true
        ]);
    
    }
}
