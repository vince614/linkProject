<?php
namespace Controllers;
use Controllers\Core\Controller;
use Model\Users;

/**
 * Class LoginController
 * @package Controllers
 */
class LoginController extends Controller
{

    /**
     * User model path
     */
    const USERS_MODEL_PATH = "Models/Users.php";

    /**
     * Model instance
     * @var Users
     */
    private $_usersModel;

    /**
     * LoginController constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        if ($this->isLogin()) header("Location: ./dashboard");
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
        require_once self::USERS_MODEL_PATH;
        $this->_usersModel = new Users();
    }

    /**
     * Before render
     */
    private function _beforeRender()
    {
        // Redirect to dashbord if login
        if ($this->isLogin()) {
            header("Location:" . $this->getHost() . "/dashboard");
        }

        if ($request = $this->getPost()) {

            // Get POST request
            $email = $request['email'];
            $password = $request['password'];
            $remember = isset($request['remember']);

            // Try to login
            $login = $this->_usersModel->login($email, $password);

            if (!$login) {
                $this->setVar('errors', $this->_usersModel->getErrors());
                return;
            }

            // Redirect to dashbord
            header("Location:" . $this->getHost() . "/dashboard");
        }
    }

}