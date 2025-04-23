<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Payment Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Payment extends Model
{
    protected $primaryKey = "payment_id";
    protected $fillable = ['payment_id', 'user_id', 'bank_name', 'account_number', 'account_name', 'balance', 'status'];

    public function addToTransaction($transaction_id)
    {
        $sql = "
            UPDATE payments p
            INNER JOIN events e ON p.user_id = e.user_id
            INNER JOIN transactions t ON e.event_id = t.event_id
            SET p.balance = p.balance + t.amount
            WHERE t.transaction_id = :transaction_id
        ";
        return $this->query($sql, ['transaction_id' => $transaction_id], "assoc");
    }

    public function deduct_amount($amount, $payment_id)
    {
        return $this->query("UPDATE payments SET balance = balance - $amount WHERE payment_id = :payment_id;", ['payment_id' => $payment_id], "assoc");
    }
}