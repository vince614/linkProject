<?php
namespace Controllers;
use Controllers\Core\Controller;
use Model\Stats;

/**
 * Class IndexController
 * @package Controllers
 */
class IndexController extends Controller
{

    /**
     * Model instance
     * @var Stats
     */
    private $_statsModel;

    /**
     * User model path
     */
    const STATS_MODEL_PATH = 'Models/Stats.php';

    /**
     * IndexController constructor.
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

    /**
     * Init Model
     */
    private function _initModels()
    {
        require_once self::STATS_MODEL_PATH;
        $this->_statsModel = new Stats();
    }

    /**
     * Before render
     */
    private function _beforeRender()
    {
        // Set values
        $this->setVar('linksCount', $this->_statsModel->getLinksCount());
        $this->setVar('clicksCount', $this->_statsModel->getClicksCount());
        $this->setVar('usersCount', $this->_statsModel->getUsersCount());
    }

}