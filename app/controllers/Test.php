<?php
namespace gateweb\mvc\app\controllers;

use gateweb\mvc\core\View;

class Test extends \gateweb\mvc\core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('test/index.html');
    }
}
