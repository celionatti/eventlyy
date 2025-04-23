<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Message Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Message extends Model
{
    protected $primaryKey = "message_id";
    protected $fillable = ['message_id', 'name', 'email', 'message', 'status'];
}