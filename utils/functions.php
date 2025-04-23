<?php

use PhpStrike\app\models\Setting;

function setting($key, $default = "")
{
    $setting = new Setting();
    return $setting->get_value($key)['value'] ?? $default;
}

function auth_role($userRole, array $roles = [])
{
    if (in_array($userRole, $roles, true)) {
        return true;
    }
    return false;
}