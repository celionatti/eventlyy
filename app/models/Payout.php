<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Payout Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Payout extends Model
{
    protected $primaryKey = "payout_id";
    protected $fillable = ['payout_id', 'user_id', 'to_user', 'account_number', 'amount', 'reference_id', 'status'];
}