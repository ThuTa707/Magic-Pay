<?php

namespace App\Http\Controllers\Api;

use App\User;
use Exception;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\ProfileResource;
use App\Notifications\GeneralNotification;
use App\Http\Resources\TransactionsResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\NotificationsResource;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\NotificationDetailResource;

class PageApiController extends Controller
{

    public function profile(){

        $user = auth()->user();
        $data = new ProfileResource($user);
        return success('Success', $data);
    }

    public function transaction(Request $request){

        $transactions = Transaction::with('user', 'sourceUser')->where('user_id', Auth::id());

        if($request->type){
            $transactions = $transactions->where('type', $request->type);
        }

        if($request->date){
            $transactions = $transactions->whereDate('created_at', $request->date);
        }

        $transactions = $transactions->latest()->paginate(3);
        $data = TransactionsResource::collection($transactions)->additional(['result' => 1, 'message' => 'success']);
        return $data;
        }




    public function transactionDetail($id){

        $transaction = Transaction::with('user', 'sourceUser')->where('user_id', Auth::id())->where('transaction_id', $id)->firstOrFail();
        $data = new TransactionDetailResource($transaction);
        return success('Suuccess', $data);

    }

    public function notifications(){
        $user = User::find(Auth::id());
        $notifications = $user->notifications()->paginate(5);
        $data =NotificationsResource::collection($notifications)->additional(['result' => 1, 'message' => 'Success']);
        return $data;
    }

    public function notificationShow($id){

        $user = User::find(Auth::id());
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $data = new NotificationDetailResource($notification);
        return success('Success', $data);
    }

    public function verify(Request $request){

        $phone = $request->phone;
        $from_user = Auth::user();
        if($phone){
            if($from_user->phone != $phone){
                
                $to_user = User::where('phone', $phone)->first();
                return success('Success', [
                    'verify_number' => $to_user->phone,
                    'verify_name' => $to_user->name,
                ]);

            }
        }

        return fail('Invalid number', null);

    }


    public function transferConfirm(TransferRequest $request){

        $phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $to_user = User::where('phone', $request->phone)->first();
        $from_user = User::find(Auth::id());


        // For Transfer security (Add Main Hash for all data)
        $str = $phone . $amount . $description;
        $hash_value = hash_hmac('sha256', $str, 'magicpayKC123!@#');
        if ($hash_value !== $request->hash_value) {
            return fail('Given data is invalid', null);
        }


        if ($from_user->phone == $phone) {
            return fail('You cannot transfer to your own number', null);
        }

        if (!$to_user && !$from_user) {
             return fail('Phone number is invalid', null);
        }

        if (!$from_user->wallet && !$to_user->wallet) {
            return fail('Given data is invalid', null);
        }

        if ($from_user->wallet->amount < $amount) {
            return fail('Your money is not enough to transfer', null);
        }

        return success('Success', [

            'from_user_phone' => $from_user->phone,
            'from_user_name' => $from_user->name,
            'to_user_phone' => $to_user->phone,
            'to_user_name' => $to_user->name,
            'amount' => $amount,
            'description' => $description,
            'hash_value' => $hash_value,
        ]);
    }

    public function transferComplete(TransferRequest $request){

        $from_user = User::find(Auth::id());

        if (!$request->password) {
            return fail('Please fill your password', null);
        }

        if (!Hash::check($request->password, $from_user->password)) {
            
            return fail('Your password is incorrect', null);
        }

        $phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $to_user = User::where('phone', $request->phone)->first();
        


        // For Transfer security (Add Main Hash for all data)
        $str = $phone . $amount . $description;
        $hash_value = hash_hmac('sha256', $str, 'magicpayKC123!@#');
        if ($hash_value !== $request->hash_value) {
            return fail('Given data is invalid', null);
        }


        if ($from_user->phone == $phone) {

            return fail('You cannot transfer to your own number', null);
        }

        if (!$to_user && !$from_user) {

            return fail('Phone number is invalid', null);
        }

        if (!$from_user->wallet && !$to_user->wallet) {

            return fail('Given data is invalid', null);
        }

        if ($amount < 1000) {
            return fail('You need to transfer at least 1000MMK', null);
        }

        if ($from_user->wallet->amount < $amount) {
            return fail('Your money is not enough to transfer', null);
        }



        DB::beginTransaction();
        try {


            $from_user->wallet->decrement('amount', $amount);
            $from_user->update();


            $to_user->wallet->increment('amount', $amount);
            $to_user->update();


            $from_acc_transaction = new Transaction();
            $reference =  UUIDGenerate::refNo();
            // For from_user
            $from_acc_transaction->ref_no = $reference;
            $from_acc_transaction->transaction_id = UUIDGenerate::transactionId();
            $from_acc_transaction->user_id = $from_user->id;
            $from_acc_transaction->amount = $amount;
            $from_acc_transaction->type = 2;
            $from_acc_transaction->source_id = $to_user->id;
            $from_acc_transaction->description = $request->description;
            $from_acc_transaction->save();


            // For to_user
            $to_acc_transaction = new Transaction();
            $to_acc_transaction->ref_no = $reference;
            $to_acc_transaction->transaction_id = UUIDGenerate::transactionId();
            $to_acc_transaction->user_id = $to_user->id;
            $to_acc_transaction->amount = $amount;
            $to_acc_transaction->type = 1;
            $to_acc_transaction->source_id = $from_user->id;
            $to_acc_transaction->description = $request->description;
            $to_acc_transaction->save();



            // From Noti
            $title = 'E-money transferred!!!';
            $message = 'Your money '.number_format($amount).' MMK is transferred to '.$to_user->name;
            $sourceable_id = $from_user->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction.detail', $from_acc_transaction->transaction_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'transaction_id' => $from_acc_transaction->transaction_id,
                ],
            ];
            Notification::send([$from_user], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));
            
            // To Noti
            $title = 'E-money received!!!';
            $message = 'You receive money '.number_format($amount).' MMK from '.$from_user->name;
            $sourceable_id = $to_user->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction.detail', $from_acc_transaction->transaction_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'transaction_id' => $to_acc_transaction->transaction_id,
                ],
            ];
            
            Notification::send([$to_user], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));



            DB::commit();

            return success('Success', ['transaction_id' => $from_acc_transaction->transaction_id]);
        } catch (Exception $e) {
            DB::rollback();
            return fail('Something went wrong'.$e->getMessage(), null);
        }
    }

    public function qrTransfer(Request $request){
        $from_user = auth()->user();
        $to_user = User::where('phone', $request->phone)->first();

        
        if ($to_user) {
            return success('Success', [
                'from_user_name' => $from_user->name,
                'from_user_phone' => $from_user->phone,
                'to_user_name' => $to_user->name,
                'to_user_phone' => $to_user->phone,
            ]);
        } 

        return fail('QR code is invalid', null);
    }

    public function qrTransferConfirm(Request $request){
        $phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $to_user = User::where('phone', $request->phone)->first();
        $from_user = User::find(Auth::id());


        // For Transfer security (Add Main Hash for all data)
        $str = $phone . $amount . $description;
        $hash_value = hash_hmac('sha256', $str, 'magicpayKC123!@#');
        if ($hash_value !== $request->hash_value) {
            return fail('Given data is invalid', null);
        }


        if ($from_user->phone == $phone) {
            return fail('You cannot transfer to your own number', null);
        }

        if (!$to_user && !$from_user) {
             return fail('Phone number is invalid', null);
        }

        if (!$from_user->wallet && !$to_user->wallet) {
            return fail('Given data is invalid', null);
        }

        if ($from_user->wallet->amount < $amount) {
            return fail('Your money is not enough to transfer', null);
        }

        return success('Success', [

            'from_user_phone' => $from_user->phone,
            'from_user_name' => $from_user->name,
            'to_user_phone' => $to_user->phone,
            'to_user_name' => $to_user->name,
            'amount' => $amount,
            'description' => $description,
            'hash_value' => $hash_value,
        ]);
    }

    public function qrTransferComplete(Request $request){
        $from_user = User::find(Auth::id());

        if (!$request->password) {
            return fail('Please fill your password', null);
        }

        if (!Hash::check($request->password, $from_user->password)) {
            
            return fail('Your password is incorrect', null);
        }

        $phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $to_user = User::where('phone', $request->phone)->first();
        


        // For Transfer security (Add Main Hash for all data)
        $str = $phone . $amount . $description;
        $hash_value = hash_hmac('sha256', $str, 'magicpayKC123!@#');
        if ($hash_value !== $request->hash_value) {
            return fail('Given data is invalid', null);
        }


        if ($from_user->phone == $phone) {

            return fail('You cannot transfer to your own number', null);
        }

        if (!$to_user && !$from_user) {

            return fail('Phone number is invalid', null);
        }

        if (!$from_user->wallet && !$to_user->wallet) {

            return fail('Given data is invalid', null);
        }

        if ($amount < 1000) {
            return fail('You need to transfer at least 1000MMK', null);
        }

        if ($from_user->wallet->amount < $amount) {
            return fail('Your money is not enough to transfer', null);
        }



        DB::beginTransaction();
        try {


            $from_user->wallet->decrement('amount', $amount);
            $from_user->update();


            $to_user->wallet->increment('amount', $amount);
            $to_user->update();


            $from_acc_transaction = new Transaction();
            $reference =  UUIDGenerate::refNo();
            // For from_user
            $from_acc_transaction->ref_no = $reference;
            $from_acc_transaction->transaction_id = UUIDGenerate::transactionId();
            $from_acc_transaction->user_id = $from_user->id;
            $from_acc_transaction->amount = $amount;
            $from_acc_transaction->type = 2;
            $from_acc_transaction->source_id = $to_user->id;
            $from_acc_transaction->description = $request->description;
            $from_acc_transaction->save();


            // For to_user
            $to_acc_transaction = new Transaction();
            $to_acc_transaction->ref_no = $reference;
            $to_acc_transaction->transaction_id = UUIDGenerate::transactionId();
            $to_acc_transaction->user_id = $to_user->id;
            $to_acc_transaction->amount = $amount;
            $to_acc_transaction->type = 1;
            $to_acc_transaction->source_id = $from_user->id;
            $to_acc_transaction->description = $request->description;
            $to_acc_transaction->save();



            // From Noti
            $title = 'E-money transferred!!!';
            $message = 'Your money '.number_format($amount).' MMK is transferred to '.$to_user->name;
            $sourceable_id = $from_user->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction.detail', $from_acc_transaction->transaction_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'transaction_id' => $from_acc_transaction->transaction_id,
                ],
            ];
            Notification::send([$from_user], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));
            
            // To Noti
            $title = 'E-money received!!!';
            $message = 'You receive money '.number_format($amount).' MMK from '.$from_user->name;
            $sourceable_id = $to_user->id;
            $sourceable_type = Transaction::class;
            $web_link = route('transaction.detail', $from_acc_transaction->transaction_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => [
                    'transaction_id' => $to_acc_transaction->transaction_id,
                ],
            ];
            
            Notification::send([$to_user], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));



            DB::commit();

            return success('Success', ['transaction_id' => $from_acc_transaction->transaction_id]);
        } catch (Exception $e) {
            DB::rollback();
            return fail('Something went wrong'.$e->getMessage(), null);
        }
    }

}
