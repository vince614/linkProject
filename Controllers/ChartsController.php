<?php

namespace Controllers;

use Controllers\Core\Controller;
use Model\Charts;

/**
 * Class ChartsController
 * @package Controllers
 */
class ChartsController extends Controller
{

    /**
     * Model instance
     * @var Charts
     */
    private $_chartsModel;

    /**
     * User model path
     */
    const CHARTS_MODEL_PATH = 'Models/Charts.php';

    public function __construct($path)
    {
        parent::__construct();
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
    }

    /**
     * Init models
     */
    private function _initModels()
    {
        require_once self::CHARTS_MODEL_PATH;
        $this->_chartsModel = new Charts();
    }

    private function _beforeRender()
    {
        // Set default view value
        $this->setVar('view', "all");
        if ($this->isLogin()) {
            if ($request = $this->getPost()) {
                // If get code change view
                if (isset($request['code'])) {
                    $code = $request['code'];
                    if ($this->_chartsModel->checkIfCodeExist($code)) {

                        // Change view
                        $this->setVar('view', $code);
                    }
                }

                if (isset($request['code'])
                    && isset($request['chart'])) {

                    $code = $request['code'];
                    $date = isset($request['date']) ? $request['date'] : false;
                    $chart = $request['chart'];

                    // Get user informations
                    $user = $this->getUserLogged();
                    $result = [];

                    // Switch chart POST request
                    switch ($chart) {
                        case 'clicksCount':
                            $result = $this->_chartsModel->getClickChartArea($user['email'], $code, $date);
                            break;
                        case 'deviceClick':
                            $result = $this->_chartsModel->getDeviceClicks($user['email'], $code, $date);
                            break;
                        case 'locationClick':
                            $result = $this->_chartsModel->getLocationClick($user['email'], $code);
                            break;
                        case 'browserClicks':
                            $result = $this->_chartsModel->getBrowserClick($user['email'], $code);
                    }
                    echo json_encode($result);
                    exit;
                }
            }
        }
    }

}