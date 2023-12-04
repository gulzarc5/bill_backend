<?php

namespace App\Services;

use App\Models\Buyer;
use App\Models\Transaction;

class WalletService
{
    // 1 means credit, 2 means debit
    // private $bill_id,$user_id,$amount,$type,$comment;

    // public function __construct($bill_id,$user_id,$amount,$type,$comment) {
    //     $this->bill_id = $bill_id;
    //     $this->user_id = $user_id;
    //     $this->amount = $amount;
    //     $this->type = $type;
    //     $this->comment = $comment;
    // }

    public function beginWalletTransaction($bill_id,$user_id,$amount,$type,$comment)
    {
        $buyer = Buyer::where('id', $user_id)->first();
        if ($buyer) {
            $buyer->outstanding_amount = $type == "1" ? $buyer->outstanding_amount + $amount : $buyer->outstanding_amount - $amount;
            if($buyer->save()){
                $transaction = new Transaction();
                $transaction->client_id = $user_id;
                $transaction->bill_id = $bill_id;
                $transaction->type = $type;
                $transaction->comment = $comment;
                $transaction->amount = $amount;
                $transaction->outstanding_amount = $buyer->outstanding_amount;
                $transaction->save();
            }
        }
        return true;
    }


}
