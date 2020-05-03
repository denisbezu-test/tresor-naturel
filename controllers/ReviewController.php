<?php

require_once __DIR__ . '/../classes/Controller.php';
require_once __DIR__ . '/../classes/Model/UserModel.php';
require_once __DIR__ . '/../classes/Model/ReviewModel.php';
require_once __DIR__ . '/../classes/Model/ArticleModel.php';

class ReviewController extends Controller
{

    public function run()
    {
        $this->showForm();
    }

    protected function showForm()
    {
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'review/review.html',
            'review_form' => 'review/review_form.html',
            'review_not_connected' => 'review/review_not_connected.html',
            'review_success' => 'review/review_success.html',
        ]);
        $loginModel = new UserModel($this->connection);
        if (!$loginModel->isConnected()) {
            $template->assign_var_from_handle('review_not_connected', 'review_not_connected');
        } else {
            if ($_GET['action'] == 'evaluate') {
                $reviewModel = new ReviewModel($this->connection);
                $idArticle = $_POST['article'];
                $score = $_POST['score'];
                $reviewModel->addReview($_SESSION['id'], $idArticle, $score);
                $template->assign_var_from_handle('review_success', 'review_success');
            }
            $articleModel = new ArticleModel($this->connection);
            $articles = $articleModel->getAll('name');
            foreach ($articles as $article) {
                $template->assign_block_vars('article', [
                    'value' => $article['id'],
                    'name' => $article['name']
                ]);
            }
            $template->assign_var_from_handle('review_form', 'review_form');
        }


        $template->pparse('body');
    }
}