<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AdminArticleController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;

use celionatti\Bolt\Controller;
use PhpStrike\app\models\Article;

use celionatti\Bolt\Illuminate\Meta\Meta;
use celionatti\Bolt\Illuminate\Support\Upload;
use celionatti\Bolt\Illuminate\Support\Image;

use celionatti\Bolt\Pagination\Pagination;

class AdminArticleController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Manage Articles");

        $this->setCurrentUser(auth_user());
        if(!$this->currentUser || $this->currentUser['role'] !== "admin") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/admin");
        }
    }

    public function manage()
    {
        $article = new Article();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $articles = $article->paginate($page, 5, ['status' => 'publish'], ['created_at' => "DESC"]);

        $pagination = new Pagination($articles['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'articles' => $articles['data'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/articles/manage", $view);
    }

    public function create(Request $request)
    {
        $view = [
            'errors' => getFormMessage(),
            'articles' => retrieveSessionData('article_data'),
            'statusOpts' => [
                'draft' => 'Draft',
                'publish' => 'Publish',
            ],
            'upload_type' => $request->get('ut'),
        ];

        unsetSessionArrayData(['article_data']);

        $this->view->render("admin/articles/create", $view);
    }

    public function insert(Request $request)
    {
        if ("POST" === $request->getMethod()) {
            // Proceed to create article if validation passes
            $article = new Article();

            $rules = [
                'title' => 'required',
                'content' => 'required',
                'tag' => 'required',
                'image' => 'required',
                'contributors' => 'required',
                'status' => 'required',
                // 'user_id' => 'required',
            ];

            // Load and validate data
            $attributes = $request->loadDataExcept(['content']);
            $attributes['content'] = $_POST['content'];
            $attributes['article_id'] = bv_uuid();
            $attributes['user_id'] = $this->currentUser['user_id'] ?? null;
            $attributes['meta_title'] = strtolower(Meta::metaTitle($attributes['title'], $attributes['content']));
            $attributes['meta_description'] = strtolower(Meta::metaDescription($attributes['content']));
            $attributes['meta_keywords'] = strtolower(Meta::metaKeywords($attributes['title'], $attributes['content']));

            if($request->get("ut") === "file") {
                $upload = new Upload("uploads/articles");
                $article_image = $upload->uploadFile("image");
                $attributes['image'] = $article_image['file'];

                if($article_image['success']) {
                    $image = new Image();
                    $image->resize($attributes['image']);
                }
            }

            if (!$request->validate($rules, $attributes)) {
                // delete uploaded image
                if ($request->get("ut") === "file") {
                    $upload->delete($attributes['image']);
                }

                storeSessionData('article_data', $attributes);
                setFormMessage($request->getErrors());
                redirect(URL_ROOT . "/admin/articles/create?ut={$request->get('ut')}");
                return; // Ensure the method exits after redirect
            }

            if ($article->create($attributes) && $article_image['success']) {
                // Success: Redirect to manage page
                toast("success", "Article Created Successfully");
                redirect(URL_ROOT . "/admin/articles/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'Event creation failed!']);
                redirect(URL_ROOT . "/admin/articles/manage");
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $article = new Article();

        $fetchData = $article->find($id)->toArray();

        $view = [
            'errors' => getFormMessage(),
            'article' => $fetchData ?? retrieveSessionData('article_data'),
            'statusOpts' => [
                'draft' => 'Draft',
                'publish' => 'Publish',
            ],
            'upload_type' => $request->get('ut'),
        ];

        unsetSessionArrayData(['article_data']);

        $this->view->render("admin/articles/edit", $view);
    }

    public function update(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            return; // Early return for non-POST requests
        }

        $article = new Article();
        $fetchData = $article->find($id);

        // Article not found
        if (!$fetchData) {
            toast("info", "Article Not Found!");
            redirect(URL_ROOT . "/admin/articles/manage");
            return;
        }

        // Validation rules
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'tag' => 'required',
            'image' => 'required',
            'contributors' => 'required',
            'status' => 'required',
            // 'user_id' => 'required',
        ];

        // Load and prepare data
        $attributes = $request->loadDataExcept(['content']);
        $attributes['content'] = $_POST['content'];
        $attributes['meta_title'] = strtolower(Meta::metaTitle($attributes['title'], $attributes['content']));
        $attributes['meta_description'] = strtolower(Meta::metaDescription($attributes['content']));
        $attributes['meta_keywords'] = strtolower(Meta::metaKeywords($attributes['title'], $attributes['content']));

        // Handle file upload if ut=file
        $attributes['image'] = $this->handleThumbnail($request, $fetchData, $attributes);

        // Validate request data
        if (!$request->validate($rules, $attributes)) {
            // delete uploaded image
            if ($request->get("ut") === "file") {
                $upload->delete($attributes['image']);
            }

            storeSessionData('article_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/admin/articles/edit/{$fetchData['article_id']}?ut={$request->get('ut')}");
            return; // Ensure exit after redirect
        }

        // Update the event
        if ($article->update($attributes, $id)) {
            toast("success", "Article Updated Successfully");
            redirect(URL_ROOT . "/admin/articles/manage");
        } else {
            setFormMessage(['error' => 'Article update process failed!']);
            redirect(URL_ROOT . "/admin/articles/manage");
        }
    }

    public function delete(Request $request, $id)
    {
        if("POST" === $request->getMethod()) {
            $article = new Article();
            $fetchData = $article->find($id);

            // Define the redirect URL once to avoid repetition
            $redirectUrl = URL_ROOT . "/admin/articles/manage";

            // Check if the article exists
            if (!$fetchData) {
                toast("info", "Article Not Found!");
                return redirect($redirectUrl);
            }

            // Attempt to delete the image if it exists
            $upload = new Upload("uploads/articles");
            if(file_exists($fetchData->image)) {
                if (!$upload->delete($fetchData->image)) {
                    setFormMessage(['error' => 'Image delete failed!']);
                }
            }

            // Delete the article
            if ($article->delete($id)) {
                toast("success", "Article Deleted Successfully");
            } else {
                setFormMessage(['error' => 'Article delete process failed!']);
            }

            // Redirect back to the articles management page in either case
            return redirect($redirectUrl);
        }
    }

    private function handleThumbnail(Request $request, $fetchData, $attributes)
    {
        $upload = new Upload("uploads/articles");

        if ($request->get("ut") === "file") {
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $article_image = $upload->uploadFile("image");
                if ($article_image['success']) {
                    if (!is_null($fetchData->image)) {
                        $upload->delete($fetchData->image, true);
                    }
                    $image = new Image();
                    $image->resize($article_image['file']);
                    return $article_image['file'];
                }
                throw new Exception('Image upload failed!');
            }
            return $fetchData->image;
        }

        if ($request->get("ut") === "link") {
            if (file_exists($fetchData->image)) {
                $upload->delete($fetchData->image);
            }
            $imageValue = $_POST['image'];
            if (filter_var($imageValue, FILTER_VALIDATE_URL)) {
                return $imageValue;
            }
            throw new Exception('Invalid image link!');
        }

        return $fetchData->image;
    }
}