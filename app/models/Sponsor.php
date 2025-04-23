<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Sponsor Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Sponsor extends Model
{
    protected $primaryKey = "sponsor_id";
    protected $fillable = ['sponsor_id', 'name', 'image'];
}