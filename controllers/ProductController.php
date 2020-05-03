<?php

require_once __DIR__ . '/../classes/Controller.php';
require_once __DIR__ . '/../classes/Model/ArticleModel.php';
require_once __DIR__ . '/../classes/Model/BodyModel.php';
require_once __DIR__ . '/../classes/Model/TypeModel.php';

class ProductController extends Controller
{
    public function run()
    {
        $action = empty($_GET['action']) ? null : $_GET['action'];

        switch ($action) {
            case 'save':
                $this->saveArticle();
                break;
            case 'edit':
                $this->showEdit();
                break;
            case 'delete':
                $this->deleteArticle();
                break;
            case 'add':
                $this->showAdd();
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
            'body' => 'product/product.html',
            'product_table' => 'product/product_table.html'
        ]);
        $articleModel = new ArticleModel($this->connection);
        $articles = $articleModel->getAll($sort);
        foreach ($articles as $article) {
            $template->assign_block_vars('article', [
                'id' => $article['id'],
                'name' => $article['name'],
                'photo' => empty($article['photo']) ? 'Indisponible' : '<img src="/images/thumbs/articles/' . $article['photo'] . '" alt="' . $article['name'] . '">',
                'price' => $article['price'],
                'body' => $article['body'],
                'type' => $article['type'],
                'score' => number_format($article['score'], 2),
            ]);
        }
        $template->assign_var_from_handle('product_table', 'product_table');
        $template->pparse('body');
    }

    protected function deleteArticle()
    {
        if (empty($_POST['id'])) {
            $this->showList();
        }
        $id = $_POST['id'];
        $articleModel = new ArticleModel($this->connection);
        $articleModel->deleteById($id);
        $this->showList();
    }

    public function showEdit()
    {
        if (empty($_GET['id'])) {
            return;
        }
        $articleId = $_GET['id'];
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'product/product.html',
            'product_edit' => 'product/product_edit.html'
        ]);
        $articleModel = new ArticleModel($this->connection);
        $article = $articleModel->getById($articleId);
        $bodyModel = new BodyModel($this->connection);
        $typeModel = new TypeModel($this->connection);
        $selectedBody = $bodyModel->getById($article['id_body']);
        $selectedType = $typeModel->getById($article['id_type']);
        $bodies = $bodyModel->getAll();
        $types = $typeModel->getAll();

        $template->assign_vars([
            'id' => $article['id'],
            'name' => $article['name'],
            'photo' => $article['photo'],
            'price' => $article['price'],
            'selectedBodyValue' => $selectedBody['id'],
            'selectedBodyName' => $selectedBody['name'],
            'selectedTypeValue' => $selectedType['id'],
            'selectedTypeName' => $selectedType['name'],
        ]);

        foreach ($types as $type) {
            $template->assign_block_vars('types', [
                'value' => $type['id'],
                'name' => $type['name']
            ]);
        }

        foreach ($bodies as $body) {
            $template->assign_block_vars('bodies', [
                'value' => $body['id'],
                'name' => $body['name']
            ]);
        }

        $template->assign_var_from_handle('product_edit', 'product_edit');
        $template->pparse('body');
    }

    protected function saveArticle()
    {
        $articleModel = new ArticleModel($this->connection);
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $body = $_POST['body'];
        $type = $_POST['type'];
        if (empty($name) || empty($price) || empty($body) || empty($type)) {
            $this->showList();
        }
        if (empty($id)) {
            $articleModel->add($name, $price, $body ,$type);
        } else {
            $articleModel->update($id, $name, $price, $body ,$type);
        }
        $this->showList();
    }

    protected function showAdd()
    {
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'product/product.html',
            'product_add' => 'product/product_add.html'
        ]);
        $bodyModel = new BodyModel($this->connection);
        $typeModel = new TypeModel($this->connection);
        $bodies = $bodyModel->getAll();
        $types = $typeModel->getAll();

        foreach ($types as $type) {
            $template->assign_block_vars('types', [
                'value' => $type['id'],
                'name' => $type['name']
            ]);
        }

        foreach ($bodies as $body) {
            $template->assign_block_vars('bodies', [
                'value' => $body['id'],
                'name' => $body['name']
            ]);
        }

        $template->assign_var_from_handle('product_add', 'product_add');
        $template->pparse('body');
    }
}
