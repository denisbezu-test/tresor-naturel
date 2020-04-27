<?php

require_once __DIR__ . '/../classes/Controller.php';
require_once __DIR__ . '/../classes/Model/UserModel.php';

class UserController extends Controller
{

    public function run()
    {
        $action = !empty($_GET['action']) ? $_GET['action'] : null;

        if (empty($action)) {
            $this->showUserInfoPage();
        } else {
            $this->modifyUserInfo();
        }
    }

    protected function showUserInfoPage()
    {
        $loginModel = new UserModel($this->connection);
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'user/user.html',
            'user_form' => 'user/user_form.html',
            'user_not_logged' => 'user/user_not_logged.html'
        ]);

        if (!$loginModel->isConnected()) {
            $template->assign_var_from_handle('user_not_logged', 'user_not_logged');
        } else {
            $userModel = new UserModel($this->connection);
            $user = $userModel->getUserById($_SESSION['id']);
            $template->assign_vars([
                'login' => $user['login'],
                'avatar_path' => $user['avatar']
            ]);
            $template->assign_var_from_handle('user_form', 'user_form');
        }
        $template->pparse('body');
    }

    protected function modifyUserInfo()
    {
        $loginModel = new UserModel($this->connection);
        $user = $loginModel->getUserById($_SESSION['id']);
        if (!empty($user)) {
            $template = new Template(__DIR__ . '/../views/templates');
            $template->set_filenames([
                'body' => 'user/user.html',
                'user_error' => 'user/user_error.html',
                'user_not_logged' => 'user/user_not_logged.html',
                'user_passwords' => 'user/user_passwords.html',
                'user_success' => 'user/user_success.html',
            ]);
            $login = !empty($_POST['login']) ? $_POST['login'] : null;
            $password = !empty($_POST['password']) ? $_POST['password'] : null;
            $passwordConfirmation = !empty($_POST['password_confirmation']) ? $_POST['password_confirmation'] : null;
            if (empty($login) || empty($password) || empty($passwordConfirmation) || empty($_FILES['avatar']['name'])) {
                $template->assign_var_from_handle('user_error', 'user_error');
            } else if ($password !== $passwordConfirmation) {
                $template->assign_var_from_handle('user_passwords', 'user_passwords');
            } else {
                $registerModel = new UserModel($this->connection);
                $registerModel->modifyUser($login, $password);
                $template->assign_var_from_handle('user_success', 'user_success');
            }
            $template->pparse('body');
        }
    }
}