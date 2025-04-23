<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== Article Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class Article extends Model
{
    protected $primaryKey = "article_id";
    protected $fillable = ['article_id', 'title', 'content', 'tag', 'user_id', 'image', 'meta_title', 'meta_description', 'meta_keywords', 'contributors', 'views', 'status'];

    public function increase_view($id)
    {
        return $this->query("UPDATE articles SET views = views + 1 WHERE article_id = :id;", ['id' => $id], "assoc");
    }

    public function featured_article()
    {
        return $this->query("SELECT * FROM articles WHERE featured = :featured ORDER BY created_at DESC LIMIT 1;", ['featured' => 'yes'], "assoc")['result'][0];
    }

    public function popular_articles()
    {
        return $this->query("SELECT * FROM articles WHERE views > 0 ORDER BY views DESC LIMIT 4;", [], "assoc")['result'];
    }
}