<?php

namespace App\Helpers;

use App\Transaction;
use App\Wallet;

class UUIDGenerate {
    
    public static function accountNumber(){

        $number = mt_rand(1000000000000000, 9999999999999999);
        if(Wallet::where('account_number', $number)->exists()){
            
            // random number without repitition
            // To run func again if acc number exists in wallet table

            self::accountNumber();

        }

        return $number;
    }


    public static function refNo(){

        $number = mt_rand(1000000000000000, 9999999999999999);
        $check = Transaction::where('ref_no', $number)->exists();
        if($check){
            self::refNo();
        }

        return $number;
    }

    public static function transactionId(){

        $number = mt_rand(1000000000000000, 9999999999999999);
        if(Transaction::where('transaction_id', $number)->exists()){
            self::transactionId();
        }

        return $number;
    }







}


?>