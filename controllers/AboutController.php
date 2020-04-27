<?php

require_once __DIR__ . '/../classes/Controller.php';

class AboutController extends Controller
{
    public function run()
    {
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'about.html'
        ]);
        $template->pparse('body');
    }
}