<?php

namespace App\Http\Controllers;

use App\Models\DataEntry;
use App\Models\PhoneNumber;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;


class DataEntryController extends Controller
{

    public function store(Request $request)
    {

        $exchangeId = session('exchange_id');
        $userId = session('user_id');
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'customer_phone' => 'nullable',
            'customer_feedback' => 'required',
            'customer_amount' =>'required',
            'task_name' =>'required',
            'if_walk' =>'nullable',
            'phone_id' =>'nullable',
        ]);
        $phoneID = (int) $request->input('phone_id');
        if($request->if_walk){
            $existPhone = PhoneNumber::where('phone_number',$request->customer_phone )->exists();
            if($existPhone){
                return response()->json(['error' => 'Phone number already exists'], 404);
            }else{
                $tp = new PhoneNumber();
                $tp->phone_number= $request->customer_phone;
                $tp->status= 'deactive';
                $tp->user_id= $userId;
                $tp->exchange_id= $exchangeId;
                $tp->save();
                $phoneID = $tp->id;
            }
        }        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        try {
            $name = $request->input('customer_name');
            $phone_id = $phoneID ;
            if(!$phone_id){
                return response()->json(['status' => 'failed', 'message'=>"number is stored but not data entry is stored"]);
            }
            $feedback = $request->input('customer_feedback');
            $amount = $request->input('customer_amount');
            $task_name = $request->input('task_name');
            $dataEntry = DataEntry::where('phone_id', $phone_id)->first();

            // IDOR Protection: Verify the phone number belongs to the current user's exchange
            $phoneNumberRecord = PhoneNumber::find($phone_id);
            if (!$phoneNumberRecord || $phoneNumberRecord->exchange_id != $exchangeId) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized access to this record.'], 403);
            }

            if ($dataEntry) {
                $createdAt = Carbon::parse($dataEntry->created_at);
                $currentDate = Carbon::now();


                $de = DataEntry::find($dataEntry->id);
                $de->name = $name;
                $de->feedback = $feedback;
                $de->amount = $amount;
                $de->task_name = $task_name;
                $de->exchange_id = $exchangeId;
                $de->user_id = $userId;
                $de->update();
            }
            else{  
                $dataEntry1 = new DataEntry();
                $dataEntry1->name = $name;
                $dataEntry1->phone_id = $phone_id;
                $dataEntry1->feedback = $feedback;
                $dataEntry1->amount = $amount;
                $dataEntry1->task_name = $task_name;
                $dataEntry1->exchange_id = $exchangeId;
                $dataEntry1->user_id = $userId;
                $dataEntry1->save();
            }
            if($request->phone_id != Null){       
                $PhoneId = $request->phone_id;
                $record = PhoneNumber::where('id', $PhoneId)->first();
                if ($record) {
                    $record->status = 'deactive';
                    $record->exchange_id = $exchangeId;
                    $record->user_id = $userId;
                    $record->save();
                }
            }
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'customer_name' => 'required',
            'customer_feedback' => 'required',
            'customer_amount' =>'required',
            'task_name' =>'required',
        ]);
        try {
            $id = $request->input('id');
            $name = $request->input('customer_name');
            $feedback = $request->input('customer_feedback');
            $amount = $request->input('customer_amount');
            $task_name = $request->input('task_name');

            $dataEntry = DataEntry::find($id);
            $dataEntry->name = $name;
            $dataEntry->feedback = $feedback;
            $dataEntry->amount = $amount;
            $dataEntry->task_name = $task_name;
            $dataEntry->update();
            return  response()->json(['status' => 'success']);
            // }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getPhoneId(Request $request){
        $encryptedUserId = session('user_id');
        $encryptedPhone = $request->input('customer_phone');
        $exchange_id = User::where('id', $encryptedUserId)->value('exchange_id');
        
        $existingPhoneNumber = PhoneNumber::where('phone_number', $encryptedPhone)->first();
        if ($existingPhoneNumber) {
            return response()->json(['message' => 'Number already exist.'], 201);
        }
        
        $phoneNumber = new PhoneNumber();
        $phoneNumber->user_id = $encryptedUserId;
        $phoneNumber->phone_number = $encryptedPhone;
        $phoneNumber->exchange_id = $exchange_id;
        $phoneNumber->status = 'deactive';
        $phoneNumber->save();
        return response()->json([
            'message' => 'Number successfully added.',
            'phone_id' => $phoneNumber->id 
        ], 200);
    }
}
