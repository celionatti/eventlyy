<?php

declare(strict_types=1);

/**
 * ===============================================
 * ==================           ==================
 * ****** AdminSettingController
 * ==================           ==================
 * ===============================================
 */

namespace PhpStrike\app\controllers;

use celionatti\Bolt\Http\Request;
use celionatti\Bolt\Http\Response;
use celionatti\Bolt\Controller;
use celionatti\Bolt\Illuminate\Support\Upload;
use celionatti\Bolt\Illuminate\Support\Image;
use celionatti\Bolt\Pagination\Pagination;

use PhpStrike\app\models\Setting;
use PhpStrike\app\models\Team;

class AdminSettingController extends Controller
{
    public function onConstruct(): void
    {
        $this->view->setLayout("admin");
        $this->view->setTitle("Manage Teams");
        $this->setCurrentUser(auth_user());

        if(!$this->currentUser || $this->currentUser['role'] !== "admin") {
            toast("info", "Admin User Login Or Unauthorized!");
            redirect(URL_ROOT . "/admin");
        }
    }

    public function manage()
    {
        $setting = new Setting();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $settings = $setting->paginate($page, 5);

        $pagination = new Pagination($settings['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'settings' => $settings['data'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/settings/manage", $view);
    }

    public function create(Request $request)
    {
        $view = [
            'errors' => getFormMessage(),
            'data' => retrieveSessionData('setting_data'),
            'statusOpts' => [
                'disable' => 'Disable',
                'active' => 'Active',
            ],
        ];

        unsetSessionArrayData(['setting_data']);

        $this->view->render("admin/settings/create", $view);
    }

    public function insert(Request $request)
    {
        if("POST" !== $request->getMethod()) {
            return; // Early return for non-POST requests
        }

        $setting = new Setting();

        $rules = [
            'name' => 'required|string',
            'value' => 'required',
            'status' => 'required',
        ];

        // Load and validate data
        $attributes = $request->loadDataExcept(['value']);
        $attributes['value'] = $_POST['value'];

        if (!$request->validate($rules, $attributes)) {
            storeSessionData('setting_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/admin/settings/create");
            return; // Ensure the method exits after redirect
        }

        if ($setting->create($attributes)) {
            // Success: Redirect to manage page
            toast("success", "Setting Created Successfully");
            redirect(URL_ROOT . "/admin/settings/manage");
        } else {
            // Failed to create: Redirect to create page
            setFormMessage(['error' => 'Setting creation failed!']);
            redirect(URL_ROOT . "/admin/settings/manage");
        }
    }

    public function edit(Request $request, $id)
    {
        $setting = new Setting();

        $fetchData = $setting->find($id)->toArray();

        $view = [
            'errors' => getFormMessage(),
            'data' => $fetchData ?? retrieveSessionData('setting_data'),
            'statusOpts' => [
                'active' => 'Active',
                'inactive' => 'Inactive',
            ],
        ];

        unsetSessionArrayData(['setting_data']);

        $this->view->render("admin/settings/edit", $view);
    }

    public function update(Request $request, $id)
    {
        if("POST" === $request->getMethod()) {
            // Proceed to create category if validation passes
            $setting = new Setting();

            $fetchData = $setting->find($id);

            if (!$fetchData) {
                toast("info", "Setting Not Found!");
                redirect(URL_ROOT . "/admin/settings/manage");
            }

            $rules = [
                'name' => 'required|string',
                'value' => 'required',
                'status' => 'required',
            ];

            // Load and validate data
            $attributes = $request->loadDataExcept(['value']);
            $attributes['value'] = $_POST['value'];

            if (!$request->validate($rules, $attributes)) {
                storeSessionData('setting_data', $attributes);
                setFormMessage($request->getErrors());
                redirect(URL_ROOT . "/admin/settings/edit/{$fetchData->id}");
                return; // Ensure the method exits after redirect
            }

            if ($setting->update($attributes, $id)) {
                // Success: Redirect to manage page
                toast("success", "Setting Updated Successfully");
                redirect(URL_ROOT . "/admin/settings/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'Setting Update Process failed!']);
                redirect(URL_ROOT . "/admin/settings/manage");
            }
        }
    }

    public function delete(Request $request, $id)
    {
        if("POST" === $request->getMethod()) {
            // Proceed to create category if validation passes
            $setting = new Setting();

            $fetchData = $setting->find($id);

            if (!$fetchData) {
                toast("info", "Setting Not Found!");
                redirect(URL_ROOT . "admin/settings/manage");
            }
            if($setting->delete($id)) {
                toast("success", "Setting Deleted Successfully");
                redirect(URL_ROOT . "/admin/settings/manage");
            } else {
                // Failed to create: Redirect to create page
                setFormMessage(['error' => 'Category delete process failed!']);
                redirect(URL_ROOT . "/admin/settings/manage");
            }
        }
    }

    public function teams(Request $request)
    {
        $team = new Team();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $teams = $team->paginate($page, 5);

        $pagination = new Pagination($teams['pagination'], URL_ROOT, ['ul' => 'pagination','li' => 'page-item','a' => 'page-link']);

        $view = [
            'teams' => $teams['data'],
            'pagination' => $pagination->render("ellipses"),
        ];

        $this->view->render("admin/settings/teams", $view);
    }

    public function team_create(Request $request)
    {
        $view = [
            'errors' => getFormMessage(),
            'team' => retrieveSessionData('team_data'),
        ];

        unsetSessionArrayData(['team_data']);

        $this->view->render("admin/settings/team-create", $view);
    }

    public function team_insert(Request $request)
    {
        if ("POST" === $request->getMethod()) {
            $team = new Team();

            $rules = [
                'name' => 'required',
                'nickname' => 'required',
                'email' => 'required|email',
                'image' => 'required',
                'role' => 'required',
                'socials' => 'required',
            ];

            // Load and validate data
            $attributes = $request->loadData();
            $attributes['team_id'] = bv_uuid();

            // Get the submitted social links array
            $socialLinks = $_POST['socials'];

            // Remove empty fields and sanitize inputs
            $filteredLinks = array_filter($socialLinks, fn($link) => !empty($link));
            $safeLinks = array_map('htmlspecialchars', $filteredLinks);

            // Convert to comma-separated string
            $socialString = implode(',', $safeLinks);

            $attributes['socials'] = $socialString;

            $upload = new Upload("uploads/teams");
            $team_image = $upload->uploadFile("image");
            $attributes['image'] = $team_image['file'];

            if($team_image['success']) {
                $image = new Image();
                $image->resize($attributes['image']);
            }

            if (!$request->validate($rules, $attributes)) {
                // delete uploaded image
                $upload->delete($attributes['image']);

                storeSessionData('team_data', $attributes);
                setFormMessage($request->getErrors());
                redirect(URL_ROOT . "/admin/settings/teams/create");
                return; // Ensure the method exits after redirect
            }

            if ($team->create($attributes) && $team_image['success']) {
                // Success: Redirect to manage page
                toast("success", "New Team Member Added!");
                redirect(URL_ROOT . "/admin/settings/teams");
            } else {
                // Failed to create: Redirect to create page
                toast("error", "Team Member Creation Failed!");
                redirect(URL_ROOT . "/admin/settings/teams/create");
            }
        }
    }

    public function team_edit(Request $request, $id)
    {
        $team = new Team();

        $fetchData = $team->find($id)->toArray();

        // Split the social links into an array
        $socialLinks = isset($fetchData['socials']) ? explode(',', $fetchData['socials']) : [];

        $view = [
            'errors' => getFormMessage(),
            'team' => $fetchData ?? retrieveSessionData('team_data'),
            'links' => $socialLinks,
        ];

        unsetSessionArrayData(['team_data']);

        $this->view->render("admin/settings/team-edit", $view);
    }

    public function team_update(Request $request, $id)
    {
        if("POST" !== $request->getMethod()) {
            return;
        }

        $team = new Team();
        $fetchData = $team->find($id);

        if(!$fetchData) {
            toast("info", "Team Member Not Found!");
            redirect(URL_ROOT . "/admin/settings/teams");
            return;
        }

        // Validation rules
        $rules = [
            'name' => 'required',
            'nickname' => 'required',
            'email' => 'required|email',
            'image' => 'required',
            'role' => 'required',
            'socials' => 'required',
        ];

        $attributes = $request->loadData();

        // Get the submitted social links array
        $socialLinks = $_POST['socials'];

        // Remove empty fields and sanitize inputs
        $filteredLinks = array_filter($socialLinks, fn($link) => !empty($link));
        $safeLinks = array_map('htmlspecialchars', $filteredLinks);

        // Convert to comma-separated string
        $socialString = implode(',', $safeLinks);

        $attributes['socials'] = $socialString;

        // Handle file upload if ut=file
        $attributes['image'] = $this->handleImage($request, $fetchData, $attributes);

        // Validate request data
        if (!$request->validate($rules, $attributes)) {
            // delete uploaded image
            $upload->delete($attributes['image']);

            storeSessionData('team_data', $attributes);
            setFormMessage($request->getErrors());
            redirect(URL_ROOT . "/admin/settings/teams/edit/{$fetchData['team_id']}");
            return; // Ensure exit after redirect
        }

        // Update the event
        if ($team->update($attributes, $id)) {
            toast("success", "Team Member Updated!");
            redirect(URL_ROOT . "/admin/settings/teams");
        } else {
            toast("error", "Update Process Failed");
            redirect(URL_ROOT . "/admin/settings/teams");
        }
    }

    public function team_delete(Request $request, $id)
    {
        if("POST" === $request->getMethod()) {
            $team = new Team();
            $fetchData = $team->find($id);

            // Define the redirect URL once to avoid repetition
            $redirectUrl = URL_ROOT . "/admin/settings/teams";

            // Check if the team member exists
            if (!$fetchData) {
                toast("info", "Team Member Not Found!");
                return redirect($redirectUrl);
            }

            // Attempt to delete the image if it exists
            $upload = new Upload("uploads/teams");
            if(file_exists($fetchData->image)) {
                if (!$upload->delete($fetchData->image)) {
                    setFormMessage(['error' => 'Image delete failed!']);
                }
            }

            // Delete the team member
            if ($team->delete($id)) {
                toast("success", "Team Member Deleted!");
            } else {
                toast("error", "Delete process failed!");
            }
            // Redirect back to the teams management page in either case
            return redirect($redirectUrl);
        }
    }

    private function handleImage(Request $request, $fetchData, $attributes)
    {
        $upload = new Upload("uploads/teams");

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $team_image = $upload->uploadFile("image");
            if ($team_image['success']) {
                if (!is_null($fetchData->image)) {
                    $upload->delete($fetchData->image, true);
                }
                $image = new Image();
                $image->resize($team_image['file']);
                return $team_image['file'];
            }
            throw new Exception('Image upload failed!');
        }
        return $fetchData->image;
    }
}