<?php
namespace Controllers;
use Controllers\Core\Controller;

class RegisterController extends Controller
{

    /**
     * RegisterController constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->index($path);
    }

    /**
     * Index
     *
     * @param $path
     */
    public function index($path)
    {
        if (!isset($path)) {
            $this->notFound();
            return;
        }
        $this->_initModels();
        $this->_beforeRender();
        $this->render($path);
    }

    private function _initModels()
    {

    }

    private function _beforeRender()
    {

    }

}