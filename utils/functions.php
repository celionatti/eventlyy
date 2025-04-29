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

/**
 * Get appropriate CSS class for a status value
 *
 * @param string $status The status value to check
 * @return string CSS class name for the status
 */
function get_status_class($status) {
    // Convert status to lowercase for case-insensitive comparison
    $status = strtolower($status);

    // Define color classes for different statuses
    switch ($status) {
        case 'confirmed':
            return 'confirmed';

        case 'pending':
            return 'pending';

        case 'checked_in':
            return 'checked-in';

        case 'cancelled':
            return 'cancelled';

        case 'free':
            return 'workshop';

        case 'regular':
            return 'workshop';

        case 'premium':
            return 'vip';

        default:
            return 'general';
    }
}