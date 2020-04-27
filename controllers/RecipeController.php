<?php

require_once __DIR__ . '/../classes/Controller.php';
require_once __DIR__ . '/../classes/Model/RecipeModel.php';

class RecipeController extends Controller
{

    public function run()
    {
        $action = !empty($_GET['action']) ? $_GET['action'] : null;

        switch ($action) {
            default:
                $this->showList();
                break;
        }
    }

    protected function showList()
    {
        $sort = !empty($_GET['sort']) ? $_GET['sort'] : 'name';
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'recipe/recipe.html',
            'recipes_table' => 'recipe/recipes_table.html',
            'recipes_order_ingredients' => 'recipe/recipes_order_ingredients.html',
            'recipes_order_time' => 'recipe/recipes_order_time.html',
        ]);

        if ($sort == 'time') {
            $template->assign_var_from_handle('recipes_order_time', 'recipes_order_time');
        } else if ($sort == 'ingredients') {
            $template->assign_var_from_handle('recipes_order_ingredients', 'recipes_order_ingredients');
        }
        $recipeModel = new RecipeModel($this->connection);
        $recipes = $recipeModel->getAll($sort);
        foreach ($recipes as $recipe) {
            $template->assign_block_vars('recipe', [
                'id' => $recipe['id'],
                'name' => $recipe['name'],
                'time' => $recipe['time'],
                'ingredients' => $recipe['ingredients']
            ]);
        }
        $template->assign_var_from_handle('recipes_table', 'recipes_table');

        $template->pparse('body');
    }
}