<?php

declare(strict_types=1);

/**
 * ======================================
 * ===============        ===============
 * ===== User Model
 * ===============        ===============
 * ======================================
 */

namespace PhpStrike\app\models;

use celionatti\Bolt\Model\Model;

class User extends Model
{
    protected $primaryKey = "user_id";
    protected $fillable = ['user_id', 'first_name', 'last_name', 'email', 'phone', 'password', 'business_name', 'registration_number', 'role', 'is_blocked', 'country', 'remember_token', 'session_token', 'reset_token', 'reset_token_expiry'];

    public function generateResetToken($email)
    {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $sql = "UPDATE users SET reset_token = :token,
                    reset_token_expiry = :expiry
                WHERE email = :email";

        return $this->query($sql, ['token' => $token, 'expiry' => $expiry, 'email' => $email], "assoc");
    }

    public function verifyResetToken($token)
    {
        $sql = "SELECT * FROM users
                WHERE reset_token = :token
                AND reset_token_expiry > NOW()";

        return $this->query($sql, ['token' => $token], "assoc")['result'][0];
    }

    public function updatePassword($token, $password)
    {
        $sql = "UPDATE users
                SET password = :password,
                    reset_token = NULL,
                    reset_token_expiry = NULL
                WHERE reset_token = :token";

        return $this->query($sql, ['token' => $token, 'password' => $password], "assoc");
    }

    public function verifyCurrentPassword($userId)
    {
        $sql = "SELECT password, user_id FROM users
                WHERE user_id = :user_id";

        return $this->query($sql, ['user_id' => $userId], "assoc")['result'][0];
    }
}