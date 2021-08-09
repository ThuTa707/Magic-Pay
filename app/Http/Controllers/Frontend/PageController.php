<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Exception;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\TransferRequest;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('frontend.home', compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('frontend.profile', compact('user'));
    }

    public function password()
    {

        return view('frontend.password-update');
    }

    public function image(){
        $user = Auth::user();
        return view('frontend.image-update', compact('user'));
    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'min:8'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        $user = User::find(Auth::id());

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Send Noti
        $title = 'Password Change';
        $message = 'Your password is changed successfully!!!';
        $sourceable_id = $user->id;
        $sourceable_type = User::class;
        $web_link = url('profile');
        $deep_link = [
            'target' => 'profile',
            'parameter' => 'null',
        ];

        Notification::send([$user], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

        // Auth::logout();
        return redirect()->route('home')->with('toast', ['icon' => 'success', 'title' => 'Password Change Completely!!']);
    }

    public function imageUpdate(Request $request){

        $request->validate([
            'image' => 'required|mimes:png,jpg|file|max:2500'
        ]);

        $user = User::find(Auth::id());

        $path = "/public/profile/".$user->image;
        Storage::delete($path);

        $image = $request->file('image');
        if($image){
            $imgName = uniqid()."_".$image->getClientOriginalName();
            Storage::putFileAs('/public/profile/', $image, $imgName);

            $user->image = $imgName;
            $user->update();
            return redirect()->route('profile')->with('toast', ['icon' => 'success', 'title' => 'Image upload successfully!!']);
        }
      


    }


    public function wallet()
    {

        $user = Auth::user();
        return view('frontend.wallet', compact('user'));
    }

    public function transfer()
    {

        return view('frontend.transfer');
    }

    public function transferConfirm(TransferRequest $request)
    {

        $phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $to_user = User::where('phone', $request->phone)->first();
        $from_user = User::find(Auth::id());


        // For Transfer security (Add Main Hash for all data)
        $str = $phone . $amount . $description;
        $hash_value = hash_hmac('sha256', $str, 'magicpayKC123!@#');
        if ($hash_value !== $request->hash_hide) {
            return back()->withErrors(['fail' => 'Given data is invalid'])->withInput();
        }


        if ($from_user->phone == $phone) {

            return back()->withErrors(['phone' => 'You cannot transfer to your own number'])->withInput();
        }

        if (!$to_user && !$from_user) {

            return back()->withErrors(['phone' => 'Phone number is invalid'])->withInput();
        }

        if (!$from_user->wallet && !$to_user->wallet) {

            return back()->withErrors(['fail' => 'Given data is invalid'])->withInput();
        }

        if ($from_user->wallet->amount < $amount) {
            return back()->withErrors(['amount' => 'Your money is not enough to transfer'])->withInput();
        }

        return view('frontend.transfer-confirm', compact('to_user', 'description', 'amount'));
    }


    public function verify(Request $request)
    {

        if (Auth::user()->phone != $request->phone) {
            $user = User::where('phone', $request->phone)->first();
            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'data' => $user
                ]);
            }
        }


        return response()->json([
            'status' => 'fail',
            'message' => 'Invalid Number',
        ]);
    }

    public function passwordCheck(Request $request)
    {

        if (!$request->password) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Please fill your password.'
            ]);
        }

        if (Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
                'status' => 'success',
            ]);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Password is incorrect!!'
        ]);
    }


    public function transferComplete(TransferRequest $request)
    {
        $phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $to_user = User::where('phone', $request->phone)->first();
        $from_user = User::find(Auth::id());


        // For Transfer security (Add Main Hash for all data)
        $str = $phone . $amount . $description;
        $hash_value = hash_hmac('sha256', $str, 'magicpayKC123!@#');
        if ($hash_value !== $request->hash_hide) {
            return back()->withErrors(['fail' => 'Given data is invalid'])->withInput();
        }


        if ($from_user->phone == $phone) {

            return back()->withErrors(['fail' => 'You cannot transfer to your own number'])->withInput();
        }

        if (!$to_user && !$from_user) {

            return back()->withErrors(['fail' => 'Phone number is invalid'])->withInput();
        }

        if (!$from_user->wallet && !$to_user->wallet) {

            return back()->withErrors(['fail' => 'Given data is invalid'])->withInput();
        }

        if ($amount < 1000) {
            return back()->withErrors(['fail' => 'You need to transfer at least 1000MMK'])->withInput();
        }

        if ($from_user->wallet->amount < $amount) {
            return back()->withErrors(['fail' => 'Your money is not enough to transfer'])->withInput();
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

            return redirect()->route('transaction.detail', $from_acc_transaction->transaction_id)->with('toast', ['icon' => 'success', 'title' => 'Transfer Successfully!!!']);
        } catch (Exception $e) {
            DB::rollback();
            return back()->withErrors(['fail' => 'Something went wrong!!' . $e->getMessage()])->withInput();
        }
    }

    public function transaction(Request $request)
    {
        $transactions = Transaction::with('user', 'sourceUser')->where('user_id', Auth::id());

        // With Javascript historyPushState
        // if ($request->typeFilter) {
        //     $transactions = $transactions->where('type', $request->typeFilter);
        // } 

        // if ($request->dateFilter) {
        //     $transactions = $transactions->whereDate('created_at', $request->dateFilter);
        // }

        $transactions = $transactions->latest()->paginate(5);

        return view('frontend.transactions', compact('transactions'));
    }



    public function transactionFilter(Request $request)
    {
        $transactions = Transaction::with('user', 'sourceUser')->where('user_id', Auth::id());

        if ($request->typeFilter) {
            $transactions = $transactions->where('type', $request->typeFilter);
        }

        if ($request->dateFilter) {
            $transactions = $transactions->whereDate('created_at', $request->dateFilter);
        }

        $transactions = $transactions->latest()->paginate(5);

        return view('frontend.transactions', compact('transactions'));
    }

    public function transactionDetail($id)
    {

        $transaction = Transaction::with('user', 'sourceUser')->where('user_id', Auth::id())->where('transaction_id', $id)->first();
        return view('frontend.transaction-details', compact('transaction'));
    }



    public function transferHash(Request $request)
    {
        $str = $request->phone . $request->amount . $request->description;
        $hash_str =  hash_hmac('sha256', $str, 'magicpayKC123!@#');

        return response()->json([

            'status' => 'success',
            'data' => $hash_str,
        ]);
    }

    public function receiveQr()
    {
        return view('frontend.qrReceive');
    }

    public function scanAndPay()
    {

        return view('frontend.qrScanPay');
    }


    public function qrTransfer(Request $request)
    {

        $to_user = User::where('phone', $request->to_phone)->first();
        if ($to_user) {
            return view('frontend.qr-transfer', compact('to_user'));
        } else {
            return back()->withErrors(['fail' => 'QR code is invalid'])->withInput();
        }
    }


    public function qrTransferConfirm(TransferRequest $request)
    {

        $phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $to_user = User::where('phone', $request->phone)->first();
        $from_user = User::find(Auth::id());


        // For Transfer security (Add Main Hash for all data)
        $str = $phone . $amount . $description;
        $hash_value = hash_hmac('sha256', $str, 'magicpayKC123!@#');
        if ($hash_value !== $request->hash_hide) {
            return back()->withErrors(['fail' => 'Given data is invalid'])->withInput();
        }


        if ($from_user->phone == $phone) {

            return back()->withErrors(['fail' => 'You cannot transfer to your own number'])->withInput();
        }

        if (!$to_user && !$from_user) {

            return back()->withErrors(['phone' => 'Phone number is invalid'])->withInput();
        }

        if (!$from_user->wallet && !$to_user->wallet) {

            return back()->withErrors(['fail' => 'Given data is invalid'])->withInput();
        }

        if ($from_user->wallet->amount < $amount) {
            return back()->withErrors(['amount' => 'Your money is not enough to transfer'])->withInput();
        }

        return view('frontend.qr-transfer-confirm', compact('to_user', 'description', 'amount'));
    }


    public function qrTransferComplete(TransferRequest $request)
    {
        $phone = $request->phone;
        $amount = $request->amount;
        $description = $request->description;
        $to_user = User::where('phone', $request->phone)->first();
        $from_user = User::find(Auth::id());


        // For Transfer security (Add Main Hash for all data)
        $str = $phone . $amount . $description;
        $hash_value = hash_hmac('sha256', $str, 'magicpayKC123!@#');
        if ($hash_value !== $request->hash_hide) {
            return back()->withErrors(['fail' => 'Given data is invalid'])->withInput();
        }


        if ($from_user->phone == $phone) {

            return back()->withErrors(['fail' => 'You cannot transfer to your own number'])->withInput();
        }

        if (!$to_user && !$from_user) {

            return back()->withErrors(['fail' => 'Phone number is invalid'])->withInput();
        }

        if (!$from_user->wallet && !$to_user->wallet) {

            return back()->withErrors(['fail' => 'Given data is invalid'])->withInput();
        }

        if ($amount < 1000) {
            return back()->withErrors(['fail' => 'You need to transfer at least 1000MMK'])->withInput();
        }

        if ($from_user->wallet->amount < $amount) {
            return back()->withErrors(['fail' => 'Your money is not enough to transfer'])->withInput();
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
                    'transaction_id' => $from_acc_transaction->transaction_id,
                ],
            ];
            Notification::send([$to_user], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

            DB::commit();

            return redirect()->route('transaction.detail', $from_acc_transaction->transaction_id)->with('toast', ['icon' => 'success', 'title' => 'Transfer Successfully!!!']);
        } catch (Exception $e) {
            DB::rollback();
            return back()->withErrors(['fail' => 'Something went wrong!!' . $e->getMessage()])->withInput();
        }
    }
}
