<?php

class CSRFTokenManager
{
    public function generateCSRFToken() : string
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $token = bin2hex(random_bytes(32));

        return $token;
    }

    public function validateCSRFToken($token) : bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token))
        { unset($_SESSION['csrf_token']);
            return true;
        }
        else
        {
            return false;
        }
    }
}