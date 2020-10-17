<?php
namespace Controllers;
use Controllers\Core\Controller;
use Model\Users;

/**
 * Class RegisterController
 * @package Controllers
 */
class RegisterController extends Controller
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
     * RegisterController constructor.
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

    private function _beforeRender()
    {
        if ($request = $this->getPost()) {

            $register = $this->_usersModel->register(
                $request['username'],
                $request['mail'],
                $request['password'],
                $request['confirmPassword']
            );

            // If errors
            if (!$register) {
                $this->setVar('errors', $this->_usersModel->getErrors());
                return;
            } else {
                $this->_addSessionMessage("Votre compte à bien été crée.<br/> Vous pouvez désormais vous connecter", './login');
            }

        }
    }

}