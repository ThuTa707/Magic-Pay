<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Exception;
use App\Wallet;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function index()
    {
        return view('backend.wallet.index');
    }

    public function datatable()
    {

        $wallet = Wallet::with('user');
        return Datatables::of($wallet)

            ->editColumn('user_id', function ($each) {
                $user = $each->user;
                if ($user) {
                    return '<p>Name: ' . $user->name . '</p>' . '<p>Email: ' . $user->email . '</p>'
                        . '<p>Phone: ' . $user->phone . '</p>';
                }
            })
            ->editColumn('amount', function ($each) {

                $money =  number_format($each->amount, 2);
                return $money . "MMK";
            })
            ->editColumn('created_at', function ($each) {
                $date = $each->updated_at->format('D m Y');
                $time = $each->updated_at->format('h:ia');
                return $date . '<br>' . $time;
            })
            ->rawColumns(['user_id', 'created_at'])
            ->make(true);
    }

    public function addAmount()
    {

        $users = User::orderBy('name')->get();
        return view('backend.wallet.add-amount', compact('users'));
    }

    public function addAmountWallet(Request $request)
    {

        $request->validate(
            [
                'user' => 'required',
                'amount' => 'required|integer|min:1000',
            ],
            [   
                'user.required' => 'Please choose the user.',
                'amount.min' => 'The amount must be at least 1000MMK',
            ]
        );

        $to_user = User::with('wallet')->where('id', $request->user)->firstOrFail();
        $to_user_wallet =  $to_user->wallet;

        if (!$to_user_wallet) {
            return back()->withErrors(['fail' => 'Your choosen wallet is invalid'])->withInput();
        }

        DB::beginTransaction();
        try {
            $to_user_wallet->increment('amount', $request->amount);
            $to_user->update();

            $reference = UUIDGenerate::refNo();
            $to_acc_transaction = new Transaction();
            $to_acc_transaction->ref_no = $reference;
            $to_acc_transaction->transaction_id = UUIDGenerate::transactionId();
            $to_acc_transaction->user_id = $to_user->id;
            $to_acc_transaction->amount = $request->amount;
            $to_acc_transaction->type = 1;
            $to_acc_transaction->source_id = 0;
            $to_acc_transaction->description = $request->description;
            $to_acc_transaction->save();
            DB::commit();

            return redirect()->route('admin.wallet.index')->with('toast', ['icon' => 'success', 'title' => 'Successfully added to wallet']);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['fail' => 'Something is wrong ' . $e->getMessage()])->withInput();
        }
    }

    public function reduceAmount()
    {

        $users = User::orderBy('name')->get();
        return view('backend.wallet.reduce-amount', compact('users'));
    }

    public function reduceAmountWallet(Request $request)
    {

        $request->validate(
            [
                'user' => 'required',
                'amount' => 'required|integer|min:1',
            ],
            [   
                'user.required' => 'Please choose the user.',
                'amount.min' => 'The amount must be at least 1MMK',
            ]
        );

        $to_user = User::with('wallet')->where('id', $request->user)->firstOrFail();
        $to_user_wallet =  $to_user->wallet;

        if (!$to_user_wallet) {
            return back()->withErrors(['fail' => 'Your choosen wallet is invalid'])->withInput();
        }

        DB::beginTransaction();
        try {
            $to_user_wallet->decrement('amount', $request->amount);
            $to_user->update();

            if ($to_user_wallet->amount < $request->amount) {
                throw new Exception("Your reduced amount must be equal or smaller than wallet amount");
            }


            $reference = UUIDGenerate::refNo();
            $to_acc_transaction = new Transaction();
            $to_acc_transaction->ref_no = $reference;
            $to_acc_transaction->transaction_id = UUIDGenerate::transactionId();
            $to_acc_transaction->user_id = $to_user->id;
            $to_acc_transaction->amount = $request->amount;
            $to_acc_transaction->type = 2;
            $to_acc_transaction->source_id = 0;
            $to_acc_transaction->description = $request->description;
            $to_acc_transaction->save();
            DB::commit();

            return redirect()->route('admin.wallet.index')->with('toast', ['icon' => 'success', 'title' => 'Successfully reduced from wallet']);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['fail' => 'Something is wrong.' . $e->getMessage()])->withInput();
        }
    }
}
