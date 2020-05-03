<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/../Request/Request.php';

class TypeModel extends Model
{
    public function getAll()
    {
        $request = new Request(
            $this->pdo,
            'Get all types',
            'SELECT * FROM type'
        );

        $request->execute();
        return $request->getData();
    }

    public function getById($id)
    {
        $request = new Request(
            $this->pdo,
            'Get type by id',
            'SELECT * FROM type
                    WHERE id = ' . (int)$id
        );

        $request->executeFetch();
        return $request->getData();
    }
}