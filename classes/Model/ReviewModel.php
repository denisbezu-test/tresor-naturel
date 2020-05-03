<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/../Request/Request.php';

class ReviewModel extends Model
{
    public function getAll()
    {
        $request = new Request(
            $this->pdo,
            'Get all reviews',
            'SELECT * FROM review'
        );

        $request->execute();
        return $request->getData();
    }

    public function getById($id)
    {
        $request = new Request(
            $this->pdo,
            'Get review by id',
            'SELECT * FROM review
                    WHERE id = ' . (int)$id
        );

        $request->executeFetch();
        return $request->getData();
    }

    public function addReview($idUser, $idArticle, $score)
    {
        if (empty($idArticle)) {
            return;
        }
        $score += 1;
        $request = new Request(
            $this->pdo,
            'Add review',
            "INSERT INTO review(id_user, id_article, score) VALUES ($idUser, $idArticle, $score)"
        );
        $request->execute(false);
    }
}