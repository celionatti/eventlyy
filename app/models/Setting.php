<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Setting Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value', 'status'];

    public function get_value($key)
    {
        return $this->query("SELECT value FROM settings WHERE name = :name AND status = 'active';", ['name' => $key], "assoc")['result'][0];
    }
}