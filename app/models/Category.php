<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Category Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Category extends Model
{
    protected $table = "categories";
    protected $primaryKey = "category_id";
    protected $fillable = ['category_id', 'name', 'status'];

    public function getActiveCategories()
    {
        $sql = "SELECT * FROM categories WHERE status = :status";
        return $this->query($sql, ['status' => 'active'], "assoc")['result'];
    }
}