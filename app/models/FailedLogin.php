<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== FailedLogin Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class FailedLogin extends Model
{
    protected $table = "failed_logins";
    protected $primaryKey = "id";
    protected $fillable = ['email', 'attempts', 'blocked_until'];
}