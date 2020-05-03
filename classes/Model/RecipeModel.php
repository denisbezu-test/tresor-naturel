<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/../Request/Request.php';

class RecipeModel extends Model
{
    public function getAll($sort = 'name', $filter = null)
    {
        if ($sort == 'name') {
            $order = 'r.name';
        } else if ($sort == 'time') {
            $order = 'r.time';
        } else {
            $order = 'a.name';
        }
        $query = 'SELECT r.id, r.name, r.time, a.name as ingredients FROM recipe r
                    INNER JOIN article a on r.ingredients = a.id';
        if (!is_null($filter)) {
            $query .= ' WHERE a.id = ' . (int)$filter;
        }
        $query .= ' ORDER BY ' . $order;
        $request = new Request(
            $this->pdo,
            'Get all recipes',
            $query
        );
        $request->execute();

        return $request->getData();
    }

    public function getById($id)
    {
        $request = new Request(
            $this->pdo,
            'Get by id',
            'SELECT * from recipe
                  WHERE id = ' . (int)$id
        );
        $request->executeFetch();

        return $request->getData();
    }

    public function add($name, $time, $ingredients)
    {
        $request = new Request(
            $this->pdo,
            'Add recipe',
            "INSERT INTO recipe(name, time, ingredients) VALUES ('$name', $time, $ingredients)"
        );
        $request->execute(false);
    }

    public function update($id, $name, $time, $ingredients)
    {
        $request = new Request(
            $this->pdo,
            'Update recipe',
            "UPDATE recipe
                    SET name = '$name',
                        time = $time,
                        ingredients = $ingredients
                    WHERE id = $id"
        );
        $request->execute(false);
    }

    public function deleteById($id)
    {
        $request = new Request(
            $this->pdo,
            'Delete recipe',
            'DELETE FROM recipe
                  WHERE id = ' . (int)$id
        );
        $request->execute(false);
    }
}