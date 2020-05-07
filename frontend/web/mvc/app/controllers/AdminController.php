<?php


namespace app\controllers;


use core\Auth;
use core\BaseController;
use core\Session;

class AdminController extends BaseController
{
    public $layout = 'empty';

    public function index()
    {
        return $this->render('index');
    }

    public function login()
    {
        $login = $this->getRequest()->postParams('login');
        $password = $this->getRequest()->postParams('password');

        if ($login === 'admin' && $password === '123') {
            Auth::getInstance()->set($login);
            Session::getInstance()->setFlash('success', 'Wellcome!');
            $this->redirect('?r=site/index');
        }
        Session::getInstance()->setFlash('error', 'Auth fail');
        return $this->index();
    }

    public function logout()
    {
        Auth::getInstance()->reset();
        $this->redirect('?r=site/index');
    }
}
