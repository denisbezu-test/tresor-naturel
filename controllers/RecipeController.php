<?php

require_once __DIR__ . '/../classes/Controller.php';
require_once __DIR__ . '/../classes/Model/RecipeModel.php';
require_once __DIR__ . '/../classes/Model/ArticleModel.php';

class RecipeController extends Controller
{
    public function run()
    {
        $action = !empty($_GET['action']) ? $_GET['action'] : null;

        switch ($action) {
            case 'save':
                $this->saveRecipe();
                break;
            case 'edit':
                $this->showEdit();
                break;
            case 'delete':
                $this->deleteRecipe();
                break;
            case 'add':
                $this->showAdd();
                break;
            case 'ingredients':
                $this->showIngredients();
                break;
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

    protected function showEdit()
    {
        if (empty($_GET['id'])) {
            return;
        }
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'recipe/recipe.html',
            'recipe_edit' => 'recipe/recipe_edit.html'
        ]);
        $recipeModel = new RecipeModel($this->connection);
        $recipe = $recipeModel->getById($_GET['id']);
        $articleModel = new ArticleModel($this->connection);
        $ingredients = $articleModel->getAll();
        $selectedIngredient = $articleModel->getById($recipe['ingredients']);
        $template->assign_vars([
            'id' => $recipe['id'],
            'name' => $recipe['name'],
            'time' => $recipe['time'],
            'selectedValue' => $selectedIngredient['id'],
            'selectedName' => $selectedIngredient['name'],
        ]);
        foreach ($ingredients as $ingredient) {
            $template->assign_block_vars('ingredients', [
                'value' => $ingredient['id'],
                'name' => $ingredient['name']
            ]);
        }
        $template->assign_var_from_handle('recipe_edit', 'recipe_edit');
        $template->pparse('body');
    }

    protected function saveRecipe()
    {
        $recipeModel = new RecipeModel($this->connection);
        $id = $_POST['id'];
        $name = $_POST['name'];
        $time = $_POST['time'];
        $ingredients = $_POST['ingredients'];
        if (empty($ingredients) || empty($name) || empty($time)) {
            $this->showList();
        }
        if (empty($id)) {
            $recipeModel->add($name, $time, $ingredients);
        } else {
            $recipeModel->update($id, $name, $time, $ingredients);
        }
        $this->showList();
    }

    protected function deleteRecipe()
    {
        $id = $_POST['id'];
        if (empty($id)) {
            $this->showList();
        }
        $recipeModel = new RecipeModel($this->connection);
        $recipeModel->deleteById($id);
        $this->showList();
    }

    protected function showAdd()
    {
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'recipe/recipe.html',
            'recipe_add' => 'recipe/recipe_add.html'
        ]);
        $articleModel = new ArticleModel($this->connection);
        $ingredients = $articleModel->getAll();
        foreach ($ingredients as $ingredient) {
            $template->assign_block_vars('ingredients', [
                'value' => $ingredient['id'],
                'name' => $ingredient['name']
            ]);
        }
        $template->assign_var_from_handle('recipe_add', 'recipe_add');
        $template->pparse('body');
    }

    protected function showIngredients()
    {
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'recipe/recipe.html',
            'recipe_ingredients' => 'recipe/recipe_ingredients.html',
            'recipe_ingredients_table' => 'recipe/recipe_ingredients_table.html',
        ]);
        $filter = null;
        if (!empty($_POST['ingredient'])) {
            $filter = $_POST['ingredient'];
        }
        $recipeModel = new RecipeModel($this->connection);
        $recipes = $recipeModel->getAll('name', $filter);
        foreach ($recipes as $recipe) {
            $template->assign_block_vars('recipe', [
                'id' => $recipe['id'],
                'name' => $recipe['name'],
                'time' => $recipe['time'],
                'ingredients' => $recipe['ingredients']
            ]);
        }
        $articleModel = new ArticleModel($this->connection);
        $ingredients = $articleModel->getAll();
        foreach ($ingredients as $ingredient) {
            $template->assign_block_vars('ingredients', [
                'value' => $ingredient['id'],
                'name' => $ingredient['name']
            ]);
        }
        $template->assign_var_from_handle('recipe_ingredients', 'recipe_ingredients');
        $template->assign_var_from_handle('recipe_ingredients_table', 'recipe_ingredients_table');
        $template->pparse('body');
    }
}
