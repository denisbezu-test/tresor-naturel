<?php

require_once __DIR__ . '/../classes/Controller.php';
require_once __DIR__ . '/../classes/Request/LoginRequest.php';
require_once __DIR__ . '/../classes/Model/UserModel.php';

class LoginController extends Controller
{

    public function run()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : null;

        if (is_null($action)) {
            $this->showLoginForm();
        } else if ($action == 'login') {
            $this->doLoginAction();
        } else if ($action == 'logout') {
            $this->doLogoutAction();
        }
    }

    protected function showLoginForm()
    {
        $template = new Template(__DIR__ . '/../views/templates');
        $files['body'] = 'login/login.html';
        $files['login_form'] = 'login/login_form.html';
        $files['register_message'] = 'login/register_message.html';
        $files['login_connected'] = 'login/login_connected.html';
        $template->set_filenames($files);
        if ($this->isConnected()) {
            $template->assign_var_from_handle('login_connected', 'login_connected');
        }
        if ($this->isLoginFormVisible() && !$this->isConnected()) {
            $template->assign_var_from_handle('login_form', 'login_form');
        }
        if ($this->isRegisterMessageVisible() && !$this->isConnected()) {
            $template->assign_var_from_handle('register_message', 'register_message');
        }

        $template->pparse('body');
    }

    protected function isLoginFormVisible()
    {
        if (!isset($_POST['action'])) {
            return true;
        }

        return false;
    }

    protected function isRegisterMessageVisible()
    {
        if (!isset($_POST['action'])) {
            return true;
        }

        return false;
    }

    protected function doLoginAction()
    {
        $loginModel = new UserModel($this->connection);
        $login = $_POST['login'];
        $passwrod = $_POST['password'];
        $emptyData = empty($login) || empty($passwrod);
        if ($emptyData) {
            $template = new Template(__DIR__ . '/../views/templates');
            $template->set_filenames([
                'body' => 'login/login.html',
                'login_empty' => 'login/login_empty.html',
                'back_to_home' => 'login/back_to_home.html'
            ]);
            $template->assign_var_from_handle('login_empty', 'login_empty');
        } else {
            $loginResult = $loginModel->doLogin($login, $passwrod);
            $template = new Template(__DIR__ . '/../views/templates');
            $template->set_filenames([
                'body' => 'login/login.html',
                'login_success' => 'login/login_success.html',
                'login_error' => 'login/login_error.html',
                'back_to_home' => 'login/back_to_home.html'
            ]);
            if ($loginResult) {
                $template->assign_var_from_handle('login_success', 'login_success');
            } else {
                $template->assign_var_from_handle('login_error', 'login_error');
            }
        }

        $template->assign_var_from_handle('back_to_home', 'back_to_home');
        $template->pparse('body');
    }

    public function doLogoutAction()
    {
        $loinModel = new UserModel($this->connection);
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'login/login.html',
            'logout_success' => 'login/logout_success.html',
            'logout_error' => 'login/logout_error.html',
            'logout_footer' => 'login/logout_footer.html'
        ]);
        if ($loinModel->isConnected()) {
            $loinModel->destroySession();
            $template->assign_var_from_handle('logout_success', 'logout_success');
        } else {
            $template->assign_var_from_handle('logout_error', 'logout_error');
        }
        $template->assign_var_from_handle('logout_footer', 'logout_footer');
        $template->pparse('body');
    }

    protected function isConnected()
    {
        $loginModel = new UserModel($this->connection);

        return $loginModel->isConnected();
    }
}