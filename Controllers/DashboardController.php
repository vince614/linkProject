<?php
namespace Controllers;
use Controllers\Core\Controller;
use Model\Links;
use Model\Users;

/**
 * Class DashboardController
 */
class DashboardController extends Controller
{

    /**
     * Models path
     */
    const LINKS_MODEL_PATH = 'Models/Links.php';
    const USERS_MODEL_PATH = 'Models/Users.php';


    /**
     * Model instance
     * @var Links
     */
    private $_linksModel;

    /**
     * Model instance
     * @var Users
     */
    private $_usersModel;

    /**
     * Get code to change view
     * @var int
     */
    private $_code;

    /**
     * DashboardController constructor.
     *
     * @param $path
     * @param $code
     */
    public function __construct($path, $code = null)
    {
        // If not logged in redirect
        if (!$this->isLogin()) {
            header('Location: ./login');
        }
        if ($code) {
            $this->_code = $code;
        }
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
     * Init models
     */
    private function _initModels()
    {
        require_once self::LINKS_MODEL_PATH;
        require_once self::USERS_MODEL_PATH;
        $this->_linksModel = new Links();
        $this->_usersModel = new Users();
    }

    /**
     * Before render
     */
    private function _beforerender()
    {
        if ($login = $this->isLogin()) {
            $user = $this->getUserLogged();

            $this->setVar('user', $user);
            $this->setVar('links', $this->_linksModel->getLinks($user['email']));
            $this->setVar('stats', $this->_linksModel->getClicksCount($user['email']));
            if ($this->_code) {
                $this->setVar('codeView', $this->_code);
            }

        }

        if ($request = $this->getPost()) {
            if ($login) {
                $this->setVar('user', $this->getUserLogged());
                $url = $request['url_origin'];
                $title = $request['title'];
                $this->_linksModel->createNewLink($url, $title, $this->getUserLogged());
            }

        }
    }

}