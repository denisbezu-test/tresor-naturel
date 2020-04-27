<?php

require_once __DIR__ . '/../classes/Controller.php';

class ContactController extends Controller
{

    public function run()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : null;
        switch ($action)
        {
            case 'sendContactForm':
                $this->sendContactForm();
                break;
            default:
                $this->showContactForm();
        }
    }

    protected function sendContactForm()
    {
        //to implement
    }

    protected function showContactForm()
    {
        $template = new Template(__DIR__ . '/../views/templates');
        $template->set_filenames([
            'body' => 'contact.html'
        ]);
        $template->pparse('body');
    }
}