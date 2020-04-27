<?php

require_once __DIR__ . '/../classes/Controller.php';

class HomeController extends Controller
{
    public function run()
    {
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'home.html'
        ]);
        $template->pparse('body');
    }
}
