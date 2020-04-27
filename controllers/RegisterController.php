<?php

require_once __DIR__ . '/../classes/Controller.php';
require_once __DIR__ . '/../classes/Model/UserModel.php';

class RegisterController extends Controller
{

    public function run()
    {
        $action = !empty($_GET['action']) ? $_GET['action'] : null;

        switch ($action) {
            case 'register':
                $this->doRegisterAction();
                break;
            case 'showUnregister':
                $this->showUnregisterPage();
                break;
            case 'doUnregister':
                $this->doUnregisterAction();
                break;
            default:
                $this->showRegisterForm();
                break;
        }
    }

    protected function showRegisterForm()
    {
        $loginModel = new UserModel($this->connection);
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'register/register.html',
            'register_form' => 'register/register_form.html',
            'register_logged' => 'register/register_logged.html'
        ]);

        if ($loginModel->isConnected()) {
            $template->assign_var_from_handle('register_logged', 'register_logged');
        } else {
            $template->assign_var_from_handle('register_form', 'register_form');
        }
        $template->pparse('body');
    }

    protected function doRegisterAction()
    {
        $login = !empty($_POST['login']) ? $_POST['login'] : null;
        $password = !empty($_POST['password']) ? $_POST['password'] : null;
        $passwordConfirmation = !empty($_POST['password_confirmation']) ? $_POST['password_confirmation'] : null;

        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'register/register.html',
            'register_empty' => 'register/register_empty.html',
            'register_password_error' => 'register/register_password_error.html',
            'register_user_exists' => 'register/register_user_exists.html',
            'register_success' => 'register/register_success.html'
        ]);
        if (empty($login) || empty($password) || empty($passwordConfirmation) || empty($_FILES['avatar']['name'])) {
            $template->assign_var_from_handle('register_empty', 'register_empty');
        } else if ($password !== $passwordConfirmation) {
            $template->assign_var_from_handle('register_password_error', 'register_password_error');
        } else {
            $registerModel = new UserModel($this->connection);
            $registerResult = $registerModel->doRegister($login, $password);
            if (!$registerResult) {
                $template->assign_var_from_handle('register_user_exists', 'register_user_exists');
            } else {
                $loginModel = new UserModel($this->connection);
                $user = $loginModel->getUserById($registerResult);
                $template->assign_vars([
                    'login' => $user['login'],
                    'avatar_path' => $user['avatar']
                ]);
                $template->assign_var_from_handle('register_success', 'register_success');
            }
        }

        $template->pparse('body');
    }

    protected function doUnregisterAction()
    {
        $loginModel = new UserModel($this->connection);
        if ($loginModel->isConnected()) {
            $registerModel = new UserModel($this->connection);
            $template = new Template(__DIR__ . '/../views/templates');
            $template->set_filenames([
                'body' => 'register/unregister.html',
                'unregister_success' => 'register/unregister_success.html'
            ]);
            $registerModel->doUnregister();
            $loginModel = new UserModel($this->connection);
            $loginModel->destroySession();
            $template->assign_var_from_handle('unregister_success', 'unregister_success');
            $template->pparse('body');
        }
    }

    protected function showUnregisterPage()
    {
        $loginModel = new UserModel($this->connection);
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'register/unregister.html',
            'unregister_form' => 'register/unregister_form.html',
            'unregister_not_connected' => 'register/unregister_not_connected.html',
        ]);

        if ($loginModel->isConnected()) {
            $loginModel = new UserModel($this->connection);
            $user = $loginModel->getUserById($_SESSION['id']);
            if (!empty($user)) {
                $template->assign_vars([
                    'login' => $user['login'],
                    'password' => $user['password'],
                    'avatar_path' => $user['avatar']
                ]);
                $template->assign_var_from_handle('unregister_form', 'unregister_form');
            }
        } else {
            $template->assign_var_from_handle('unregister_not_connected', 'unregister_not_connected');
        }

        $template->pparse('body');
    }
}