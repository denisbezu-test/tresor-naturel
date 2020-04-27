<?php

require_once __DIR__ . '/../Request/LoginRequest.php';
require_once __DIR__ . '/Model.php';

class UserModel extends Model
{
    public function doLogin($login, $password)
    {
        $request = new LoginRequest(
            $this->pdo,
            'Do login',
            'SELECT * from user WHERE login = \'' . $login . '\';'
        );
        $request->executeFetch();
        if (empty($request->getData())) {
            $this->destroySession();
            return false;
        }

        if (!password_verify($password, $request->getData()['password'])) {
            $this->destroySession();
            return false;
        }
        if (empty($_SESSION)) {
            $_SESSION = [];
        }

        $_SESSION['login'] = $request->getData()['login'];
        $_SESSION['id'] = $request->getData()['id'];

        return true;
    }

    public function destroySession()
    {
        $_SESSION = [];
        session_destroy();
    }

    public function isConnected()
    {
        return !empty($_SESSION)
            && !empty($_SESSION['id'])
            && !empty($_SESSION['login']);
    }

    public function getUserById($id)
    {
        $request = new LoginRequest(
            $this->pdo,
            'Get user',
            'SELECT * from user WHERE id = \'' . $id . '\';'
        );
        $request->executeFetch();

        return $request->getData();
    }

    public function doRegister($login, $password)
    {
        $isRegistered = $this->isUserRegistered($login);
        if ($isRegistered) {
            return false;
        }
        $image = $this->uploadImage();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        $request = new Request(
            $this->pdo,
            'Add user',
            "INSERT INTO user(login, password, avatar) VALUES ('$login', '$passwordHash', '$image')"
        );
        $request->execute(false);
        return $this->pdo->lastInsertId();
    }

    public function doUnregister()
    {
        $userId = $_SESSION['id'];
        if (!empty($userId)) {
            $request = new Request(
                $this->pdo,
                'Delete user',
                'DELETE FROM user WHERE id = ' . (int)$userId
            );
            $request->execute(false);
        }
    }

    protected function isUserRegistered($login)
    {
        $request = new Request(
            $this->pdo,
            'Get user',
            'SELECT * FROM user WHERE login = \'' . $login . '\''
        );
        $request->execute();
        $user = $request->getData();
        if (!empty($user)) {
            return true;
        }

        return false;
    }

    protected function uploadImage()
    {
        $targetDir = __DIR__ . '/../../images/avatars/';
        $targetFile = $targetDir . basename($_FILES['avatar']['name']);

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['avatar']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
        if (file_exists($targetFile)) {
            $uploadOk = 0;
        }

        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg'
            && $imageFileType != 'gif') {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            return false;
        } else {
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile)) {
                return $_FILES["avatar"]["name"];
            } else {
                return false;
            }
        }
    }

    public function modifyUser($login, $password)
    {
        $user = $this->getUserById($_SESSION['id']);
        if (!empty($user)) {
            if (!empty($user['avatar'])) {
                unlink(__DIR__ . '/images/avatars/' . $user['avatar']);
            }
            $image = $this->uploadImage();
            $passwordHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $request = new Request(
              $this->pdo,
              'Update user',
              "UPDATE user
                      SET login = '$login',
                          password = '$passwordHash',
                          avatar = '$image'
                      WHERE id = " . (int)$user['id']
            );
            $request->execute(false);
            $_SESSION['login'] = $login;
        }
    }
}