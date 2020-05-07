<?php


namespace app\controllers;


use app\models\Task;
use core\Auth;
use core\BaseController;
use core\Helper;
use core\Session;
use Exception;

class SiteController extends BaseController
{
    public function index($model = null)
    {
        $allData = (new Task())->sort();
        return $this->render('index', [
            'data' => Helper::getPageData($allData),
            'model' => $model,
            'totalPages' => Helper::getPageCount($allData),
        ]);
    }

    public function add()
    {
        $model = new Task();
        $model->load($this->getRequest()->postParams());

        if (!$model->validate()) {
            Session::getInstance()->setFlash('error', $model->getFirstError());
            return $this->index($model);
        }
        $model->add();
        Session::getInstance()->setFlash('success', 'Task added');

        /**
         * @todo redirect
         */
        return $this->index();
    }

    public function get()
    {
        if (!Auth::getInstance()->has()) {
            throw new Exception('Not allowed');
        }

        if (!$model = (new Task())->getById($this->getRequest()->getParams('id'))) {
            throw new Exception('Not found');
        }

        return $this->index($model);
    }

    public function store()
    {
        if (!Auth::getInstance()->has()) {
            throw new Exception('Not allowed');
        }

        if (!$model = (new Task())->getById($this->getRequest()->getParams('id'))) {
            throw new Exception('Not found');
        }

        $model->load($this->getRequest()->postParams());
        if (!$model->validate()) {
            Session::getInstance()->setFlash('error', $model->getFirstError());
            return $this->index($model);
        }
        $model->update();
        Session::getInstance()->setFlash('success', 'Task edited');

        /**
         * @todo redirect
         */
        return $this->index();
    }
}
