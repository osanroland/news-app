<?php

namespace App\Controller;

use App\Service\NewsServiceInterface;
use Twig\Environment;

class AdminController
{
    private string $error = '';

    public function __construct(private Environment $twig, private NewsServiceInterface $newsService) {}

    private function guard(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    public function showNewsDashboard(): void
    {
        $this->guard();

        $this->error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);

        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['success']);

        $news = $this->newsService->listAllNews();
        echo $this->twig->render('admin/newsDashboard.html.twig', [
            'user' => $_SESSION['user'],
            'allNews' => $news,
            'error'  => $this->error,
            'success' => $success,
        ]);
    }

    public function create(): void
    {
        $this->guard();

        $title   = trim((string)($_POST['title'] ?? ''));
        $description = $_POST['description'] !== '' ? trim((string)$_POST['description']) : null;

        $this->validateInput($title, $description);

        try {
            $this->newsService->createNews([
                'title'   => $title,
                'description' => $description
            ]);
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error while creating news. Try again later.';
        }

        $_SESSION['success'] = 'News was successfully created!';

        header('Location: /admin', true, 303);
        exit;
    }

    public function update(string $id): void
    {
        $this->guard();

        $title       = trim((string)($_POST['title'] ?? ''));
        $description = $_POST['description'] !== '' ? trim((string)$_POST['description']) : null;

        $this->validateInput($title, $description);

        try {
            $this->newsService->updateNews(
                $id,
                [
                'title'   => $title,
                'description' => $description
                ]
            );
            $_SESSION['success'] = 'News was successfully changed!';
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error while updating news. Try again later.';
        }


        header('Location: /admin', true, 303);
        exit;
    }

    public function delete(string $id): void
    {
        $this->guard();

        try {
            $this->newsService->deleteNews((int)$id);
            $_SESSION['success'] = 'News was deleted!';
        } catch (\Throwable $e) {
            $_SESSION['error'] = 'Error while deleting news. Try again later.';
        }

        header('Location: /admin', true, 303);
        exit;
    }

    private function validateInput(string $title, string $description): void
    {
        if ($title === '' || $description === '') {
            $this->error = 'Required fields are empty.';
        }
    }
}
