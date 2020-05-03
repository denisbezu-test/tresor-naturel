<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/../Request/Request.php';

class BodyModel extends Model
{
    public function getAll()
    {
        $request = new Request(
            $this->pdo,
            'Get all bodies',
            'SELECT * FROM body'
        );
        $request->execute();

        return $request->getData();
    }

    public function getById($id)
    {
        $request = new Request(
            $this->pdo,
            'Get body by id',
            'SELECT * FROM body
                    WHERE id = ' . (int)$id
        );
        $request->executeFetch();

        return $request->getData();
    }
}