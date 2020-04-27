<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/../Request/Request.php';

class RecipeModel extends Model
{
    public function getAll($sort = 'name')
    {
        if ($sort == 'name') {
            $order = 'r.name';
        } else if ($sort == 'time') {
            $order = 'r.time';
        } else {
            $order = 'a.name';
        }
        $request = new Request(
            $this->pdo,
            'Get all recipes',
            'SELECT r.id, r.name, r.time, a.name as ingredients FROM recipe r
                    INNER JOIN article a on r.ingredients = a.id
                    ORDER BY ' . $order
        );
        $request->execute();

        return $request->getData();
    }
}