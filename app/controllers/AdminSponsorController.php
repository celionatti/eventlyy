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
use PhpStrike\app\models\Sponsor;

use celionatti\Bolt\Illuminate\Support\Upload;
use celionatti\Bolt\Illuminate\Support\Image;

use celionatti\Bolt\Pagination\Pagination;

class AdminSponsorController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Manage Sponsors");

        $this->setCurrentUser(auth_user());
        if(!$this->currentUser || $this->currentUser['role'] !== "admin") {
            toast("info", "User Unauthorized!");
            redirect(URL_ROOT . "/admin");
        }
    }

    public function manage()
    {
        $sponsor = new Sponsor();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $sponsors = $sponsor->paginate($page, 5, [], ['created_at' => "DESC"]);

        $pagination = new Pagination($sponsors['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'sponsors' => $sponsors['data'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/sponsors/manage", $view);
    }

    public function create(Request $request)
    {
        $view = [
            'errors' => getFormMessage(),
            'sponsors' => retrieveSessionData('sponsor_data'),
            'upload_type' => $request->get('ut'),
        ];

        unsetSessionArrayData(['sponsor_data']);

        $this->view->render("admin/sponsors/create", $view);
    }

    public function insert(Request $request)
    {
        if ("POST" === $request->getMethod()) {
            // Proceed to create article if validation passes
            $sponsor = new Sponsor();

            $rules = [
                'name' => 'required',
                'image' => 'required',
            ];

            // Load and validate data
            $attributes = $request->loadData();
            $attributes['sponsor_id'] = bv_uuid();

            if($request->get("ut") === "file") {
                $upload = new Upload("uploads/sponsors");
                $sponsor_image = $upload->uploadFile("image");
                $attributes['image'] = $sponsor_image['file'];

                if($sponsor_image['success']) {
                    $image = new Image();
                    $image->resize($attributes['image']);
                }
            }

            if (!$request->validate($rules, $attributes)) {
                // delete uploaded image
                if ($request->get("ut") === "file") {
                    $upload->delete($attributes['image']);
                }

                storeSessionData('sponsor_data', $attributes);
                setFormMessage($request->getErrors());
                redirect(URL_ROOT . "/admin/sponsors/create?ut={$request->get('ut')}");
                return; // Ensure the method exits after redirect
            }

            if ($sponsor->create($attributes) && $sponsor_image['success']) {
                // Success: Redirect to manage page
                toast("success", "Sponsor Created Successfully");
                redirect(URL_ROOT . "/admin/sponsors/manage");
            } else {
                // Failed to create: Redirect to create page
                toast("error", "Sponsor Creation Failed");
                redirect(URL_ROOT . "/admin/sponsors/manage");
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $sponsor = new Sponsor();

        $fetchData = $sponsor->find($id)->toArray();

        $view = [
            'errors' => getFormMessage(),
            'sponsor' => $fetchData ?? retrieveSessionData('sponsor_data'),
            'upload_type' => $request->get('ut'),
        ];

        unsetSessionArrayData(['sponsor_data']);

        $this->view->render("admin/sponsors/edit", $view);
    }

    public function update(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            return; // Early return for non-POST requests
        }

        $sponsor = new Sponsor();
        $fetchData = $sponsor->find($id);

        // Article not found
        if (!$fetchData) {
            toast("info", "Sponsor Not Found!");
            redirect(URL_ROOT . "/admin/sponsors/manage");
            return;
        }

        // Validation rules
        $rules = [
            'name' => 'required',
            'image' => 'required',
        ];

        // Load and prepare data
        $attributes = $request->loadData();

        // Handle file upload if ut=file
        $attributes['image'] = $this->handleThumbnail($request, $fetchData, $attributes);

        // Validate request data
        if (!$request->validate($rules, $attributes)) {
            // delete uploaded image
            if ($request->get("ut") === "file") {
                $upload->delete($attributes['image']);
            }

            storeSessionData('sponsor_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/admin/sponsors/edit/{$fetchData['sponsor_id']}?ut={$request->get('ut')}");
            return; // Ensure exit after redirect
        }

        // Update the sponsor
        if ($sponsor->update($attributes, $id)) {
            toast("success", "Sponsor Updated Successfully");
            redirect(URL_ROOT . "/admin/sponsors/manage");
        } else {
            toast("error", "Sponsor update process failed!");
            redirect(URL_ROOT . "/admin/sponsors/manage");
        }
    }

    public function delete(Request $request, $id)
    {
        if("POST" === $request->getMethod()) {
            $sponsor = new Sponsor();
            $fetchData = $sponsor->find($id);

            // Define the redirect URL once to avoid repetition
            $redirectUrl = URL_ROOT . "/admin/sponsors/manage";

            // Check if the sponsor exists
            if (!$fetchData) {
                toast("info", "Sponsor Not Found!");
                return redirect($redirectUrl);
            }

            // Attempt to delete the image if it exists
            $upload = new Upload("uploads/sponsors");
            if(file_exists($fetchData->image)) {
                if (!$upload->delete($fetchData->image)) {
                    setFormMessage(['error' => 'Image delete failed!']);
                }
            }

            // Delete the sponsor
            if ($sponsor->delete($id)) {
                toast("success", "Sponsor Deleted Successfully");
            } else {
                toast("error", 'Article delete process failed!');
            }

            // Redirect back to the articles management page in either case
            return redirect($redirectUrl);
        }
    }

    private function handleThumbnail(Request $request, $fetchData, $attributes)
    {
        $upload = new Upload("uploads/sponsors");

        if ($request->get("ut") === "file") {
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $sponsor_image = $upload->uploadFile("image");
                if ($sponsor_image['success']) {
                    if (!is_null($fetchData->image)) {
                        $upload->delete($fetchData->image, true);
                    }
                    $image = new Image();
                    $image->resize($sponsor_image['file']);
                    return $sponsor_image['file'];
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