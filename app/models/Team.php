<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Team Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Team extends Model
{
    protected $primaryKey = "team_id";
    protected $fillable = ['team_id', 'name', 'nickname', 'email', 'image', 'role', 'socials'];
}