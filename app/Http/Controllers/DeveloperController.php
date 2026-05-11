<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneNumber;
use App\Models\TestEntry;
use App\Models\DataEntry;
use App\Models\DupPhone;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use App\Exports\DuplicateEntriesExport;

class DeveloperController extends Controller
{
    public function index()
    {
        return view('developer.index');
    }
    function decryptData($encryptedData)
    {
        $secretKey = 'MRikam@#@2024!XY'; // Example Key
        $iv = hex2bin('00000000000000000000000000000000'); // Example IV

        try {
            // Use OpenSSL for decryption
            $decrypted = openssl_decrypt(
                base64_decode($encryptedData),
                'AES-256-CBC',
                $secretKey,
                0,
                $iv
            );

            return $decrypted ?: null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getPhoneNumbers(Request $request)
    {
        // Get the offset and limit from the request or set defaults
        $offset = 40000; // Default to 0
        $limit =  10000; // Default to 10,000

        // Fetch the records with offset and limit
        $phoneNumbers = DataEntry::all();
        // $phoneNumbers = PhoneNumber::all();
        

        // $phoneNumbers = DataEntry::offset($offset)->limit($limit)->get();

        return response()->json([
            'phoneNumbers' => $phoneNumbers,
        ]);
    }


    public function updatePhoneNumbers(Request $request)
    {
        $phoneNumbers = $request->input('phoneNumbers');


        foreach ($phoneNumbers as $phoneNumber) {
            $model = DataEntry::find($phoneNumber['id']);
            if ($model) {
                $model->timestamps = false; // Disable timestamps
                $model->phone = $phoneNumber['phoneNumber'];
                // $model->amount = $phoneNumber['amount'];
                // $model->name = $phoneNumber['name'];
                // $model->feedback = $phoneNumber['feedback'];
                $model->save();
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Phone numbers updated successfully!']);
    }



    // public function updatePhoneNumbers(Request $request)
    // {
    //     $phoneNumbers = $request->input('phoneNumbers');
    //     $deletedEntries = collect(); // Collection to store deleted entries
    
    //     foreach ($phoneNumbers as $phoneNumber) {
    //         $existingEntry = PhoneNumber::where('phone', $phoneNumber['phoneNumber'])
    //             ->where('id', '!=', $phoneNumber['id'])
    //             ->orderBy('created_at', 'asc') // Order by creation date, ascending
    //             ->first();

    
    //         $currentEntry = PhoneNumber::find($phoneNumber['id']);
    //         if (!$currentEntry) {
    //             continue; 
    //         }
    
    //         if ($existingEntry) {
    //             if ($currentEntry && $currentEntry->created_at < $existingEntry->created_at) {
    //                 // Store currentEntry details before deletion
    //                 $deletedEntries->push([
    //                     'id' => $currentEntry->id,
    //                     'phone_number' => $currentEntry->phone_number,
    //                     'user_id' => $currentEntry->user_id,
    //                     'status' => $currentEntry->status,
    //                     'exchange_id' => $currentEntry->exchange_id,
    //                     // 'task_name' => $currentEntry->task_name,
    //                     // 'amount' => $currentEntry->amount,
    //                     // 'feedback' => $currentEntry->feedback,
    //                     // 'name' => $currentEntry->name,
    //                     'created_at' => $currentEntry->created_at,
    //                     'updated_at' => $currentEntry->updated_at,
    //                 ]);
                    
    //                 $currentEntry->delete();
    //             } else {
    //                 $deletedEntries->push([
    //                     'id' => $existingEntry->id,
    //                     'phone_number' => $existingEntry->phone_number,
    //                     'user_id' => $existingEntry->user_id,
    //                     'exchange_id' => $existingEntry->exchange_id,
    //                     'status' => $existingEntry->status,
    //                     // 'task_name' => $existingEntry->task_name,
    //                     // 'amount' => $existingEntry->amount,
    //                     // 'feedback' => $existingEntry->feedback,
    //                     // 'name' => $existingEntry->name,
    //                     'created_at' => $existingEntry->created_at,
    //                     'updated_at' => $existingEntry->updated_at,
    //                 ]);
    
    //                 $existingEntry->delete();
    
    //                 // Update the current entry
    //                 if ($currentEntry) {
    //                     $currentEntry->timestamps = false;
    //                     $currentEntry->phone = $phoneNumber['phoneNumber'];
    //                     $currentEntry->save();
    //                 }
    //             }
    //         } else {
    //             // Update current entry if no duplicate exists
    //             if ($currentEntry) {
    //                 $currentEntry->timestamps = false;
    //                 $currentEntry->phone = $phoneNumber['phoneNumber'];
    //                 $currentEntry->save();
    //             }
    //         }
    //     }
    
    //     // Return the list of deleted entries as JSON
    //     if ($deletedEntries->isNotEmpty()) {
    //         return response()->json([
    //             'message' => 'Duplicate entries processed.',
    //             'deleted_entries' => $deletedEntries,
    //         ], 200);
    //     } else {
    //         return response()->json(['message' => 'No duplicate entries found or updated.'], 200);
    //     }
    // }

 

    public function gettest()
    {
        $phoneNumbers = TestEntry::all();


        return response()->json([
            'phoneNumbers' => $phoneNumbers,
        ]);
    }

    public function updatetest(Request $request)
    {
        $tsetEntries = TestEntry::all();
        $dataEntry = DataEntry::all();

        foreach ($tsetEntries as $testEntry) {
            $px = PhoneNumber::where('id', $testEntry->phone_id)->exists();
            if ($px) {
                $ex = DataEntry::where('phone_id', $testEntry->phone_id)->exists();
                if (!$ex) {
                    $de = new DataEntry();
                    $de->name = $testEntry->name;
                    $de->phone_id = $testEntry->phone_id;
                    $de->feedback = $testEntry->feedback;
                    $de->amount = $testEntry->amount;
                    $de->task_name = $testEntry->task_name;
                    $de->exchange_id = $testEntry->exchange_id;
                    $de->user_id = $testEntry->user_id;
                    $de->created_at = $testEntry->created_at;
                    $de->updated_at = $testEntry->updated_at;

                    $de->save();
                    $testEntry->delete();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Phone numbers updated successfully!']);
    }

    public function correctFormat() {}
    public function name() {}
}
