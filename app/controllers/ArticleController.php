<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** ArticleController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;
use PhpStrike\app\models\Article;
use celionatti\Bolt\Sessions\Handlers\DefaultSessionHandler as Session;
use celionatti\Bolt\Pagination\Pagination;

class ArticleController extends Controller
{
    protected $session;

    public function onConstruct(): void
    {
        $this->session = new Session();
    }

    public function articles(Request $request, Response $reponse)
    {
        $this->view->setTitle("Articles");

        $article = new Article();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $articles = $article->paginate($page, 12, ['status' => 'publish'], ['created_at' => "DESC"]);

        $pagination = new Pagination($articles['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'articles' => $articles['data'],
            'pagination' => $pagination->render("ellipses"),
            'populars' => $article->popular_articles(),
            'featured' => $article->featured_article(),
        ];

        $this->view->render("articles", $view);
    }

    public function article(Request $request, $id)
    {
        $article = new Article();

        $data = $article->find($id)->toArray();

        $this->view->setTitle("{$data['title']}");

        if($this->session->get("article_view") !== $id) {
            $this->session->remove("article_view");
        }

        if($id && !$this->session->has("article_view") && $id !== $this->session->get("article_view")) {
            $article->increase_view($id);

            // Article has not been viewed before.
            $this->session->set("article_view", $id);
        }

        $view = [
            'article' => $data,
            'populars' => $article->popular_articles(),
        ];

        $this->view->render("article", $view);
    }

    public function search(Request $request)
    {
        $search = $request->get("query");

        $this->view->setTitle("Search Article: {$search}");

        $article = new Article();

        $sql = "SELECT * FROM articles WHERE title LIKE '%$search%' OR content LIKE '%$search%' OR tag LIKE '%$search%' AND status = :status";

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $articles = $article->rawPaginate($sql, ['status' => "publish"], $page, 12);

        $pagination = new Pagination($articles['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'articles' => $articles['data']['result'],
            'count' => $articles['data']['count'],
            'populars' => $article->popular_articles(),
            'search' => $search,
        ];

        $this->view->render("pages/search", $view);
    }
}