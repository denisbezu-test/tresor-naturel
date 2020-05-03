<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/../Request/Request.php';

class ArticleModel extends Model
{
    public function getAll($sort = 'name')
    {
        if ($sort == 'name') {
            $sort = 'a.name';
        } else if ($sort == 'body') {
            $sort = 'b.name';
        } else if ($sort == 'type') {
            $sort = 't.name';
        } else if ($sort == 'score') {
            $sort = 'AVG(r.score)';
        }
        $request = new Request(
            $this->pdo,
            'Get all',
            'SELECT a.id, a.name, a.price, a.photo, b.name as body, t.name as type, AVG(r.score) - 1 as score
                    FROM article a
                             LEFT JOIN body b ON a.id_body = b.id
                             LEFT JOIN type t ON t.id = a.id_type
                             LEFT JOIN review r on a.id = r.id_article
                    GROUP BY a.id
                    ORDER BY ' . $sort
        );
        $request->execute();

        return $request->getData();
    }

    public function getById($id)
    {
        $request = new Request(
            $this->pdo,
            'Get by id',
            'SELECT * from article
                  WHERE id = ' . (int)$id
        );
        $request->executeFetch();

        return $request->getData();
    }

    public function deleteById($id)
    {
        $request = new Request(
            $this->pdo,
            'Delete reviews',
            'DELETE FROM review
                    WHERE id_article = ' . (int)$id
        );
        $request->execute(false);

        $request = new Request(
            $this->pdo,
            'Delete article',
            'DELETE FROM article
                    WHERE id = ' . (int)$id
        );
        $request->execute(false);
    }

    public function add($name, $price, $body, $type)
    {
        $image = $this->uploadImage();
        $request = new Request(
            $this->pdo,
            'Add article',
            "INSERT INTO article(name, price, photo, id_body, id_type) VALUES ('$name', $price, '$image', $body, $type)"
        );
        $request->execute(false);
    }

    public function update($id, $name, $price, $body, $type)
    {
        $query = "UPDATE article
                    SET name = '$name',
                        price = $price,
                        id_type = $type,
                        id_body = $body";
        if (!empty($_FILES['photo']['name'])) {
            $image = $this->uploadImage();
            $query .= " ,photo = '$image'";
        }
        $query .= " WHERE id = $id";
        $request = new Request(
            $this->pdo,
            'Update article',
            $query
        );
        $request->execute(false);
    }

    protected function uploadImage()
    {
        $targetDir = __DIR__ . '/../../images/thumbs/articles/';
        $targetFile = $targetDir . basename($_FILES['photo']['name']);

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg'
            && $imageFileType != 'gif') {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            return false;
        } else {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                chmod($targetFile, 0777);
                return $_FILES["photo"]["name"];
            } else {
                return false;
            }
        }
    }
}