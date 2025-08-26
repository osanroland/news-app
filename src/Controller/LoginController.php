<?php

namespace App\Controller;

use App\Config\Dotenv;
use Twig\Environment;

class LoginController
{
    public function __construct(private Environment $twig, private Dotenv $dotenv) {}

    public function showLogin(): void
    {
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);

        echo $this->twig->render('login.html.twig', ['error' => $error]);
    }

    public function doLogin(): void
    {
        $user = $_POST['username'] ?? '';
        $pass = $_POST['password'] ?? '';

        $adminUser = $this->dotenv->get('USER', 'admin');
        $adminPass = $this->dotenv->get('PASSWORD', 'test');

        if ($user === $adminUser && $pass === $adminPass) {
            $_SESSION['user'] = ['username' => $user, 'is_admin' => true];
            header('Location: /admin');
            exit;
        }

        $_SESSION['error'] = 'Wrong Login Data!';

        header('Location: /login', true, 303);
        exit;
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
