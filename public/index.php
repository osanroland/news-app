<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Repository\NewsRepository;
use App\Routing\Router;
use App\Controller\LoginController;
use App\Controller\AdminController;
use App\Service\NewsService;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Config\Dotenv;

session_start();

$loader = new FilesystemLoader(__DIR__ . '/../src/View/templates');
$twig = new Environment($loader);
$pdo = Database::getInstance()->getConnection();
$env = new Dotenv(__DIR__ . '/../.env');
$auth = new LoginController($twig, $env);
$newsRepository = new NewsRepository($pdo);
$newsService = new NewsService($newsRepository);
$admin = new AdminController($twig, $newsService);

$router = new Router();
$router->get('/', fn() => header('Location: /login'));
$router->get('/login', [$auth, 'showLogin']);
$router->post('/login', [$auth, 'doLogin']);
$router->get('/logout', [$auth, 'logout']);

$router->get('/admin', [$admin, 'showNewsDashboard']);
$router->post('/admin/news', [$admin, 'create']);
$router->post('/admin/news/{id}', [$admin, 'update']);
$router->post('/admin/news/{id}/delete', [$admin, 'delete']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
