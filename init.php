<?php
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header("Content-Security-Policy: default-src 'self'; script-src  'self'; style-src   'self'; font-src    'self'");
header('X-Frame-Options: DENY'); 
header('X-Content-Type-Options: nosniff');     
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), camera=()');
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure'   => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}