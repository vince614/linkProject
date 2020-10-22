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
        $login = $this->isLogin();
        if ($login) {
            $user = $this->getUserLogged();
            if ($request = $this->getPost()) {
                if (isset($request['type'])) {
                    $type = $request['type'];
                    switch ($type) {
                        case 'deleteCustomer':
                            $this->_usersModel->deleteUser($user['id']);
                            $this->_addSessionMessage('Votre compte à bien été supprimé');
                            break;
                        case 'changePassword':
                            $success = $this->_usersModel->changePassword($user['id'], $request['oldPassword'], $request['newPassword'], $request['newPasswordVerif']);
                            if (!$success) {
                                echo $this->_usersModel->getErrors()[0];
                            } else {
                                $this->_addSessionMessage('Votre mot de passe à bien été mis à jour !');
                            }
                            break;
                        case 'changeUsername':
                            $success = $this->_usersModel->changeUsername($user['id'], $request['username']);
                            if (!$success) {
                                echo $this->_usersModel->getErrors()[0];
                            } else {
                                $this->_addSessionMessage("Votre nom d'utilisateur à bien été mis à jour !");
                            }
                            break;
                        case 'addLink':
                            $this->_linksModel->createNewLink($request['url'], $request['title'], $user);
                            $this->_addSessionMessage("Votre lien à bien été crée !");
                            break;
                    }
                }
                exit;
            }

            // Set vars in view
            $this->setVar('user', $user);
            $this->setVar('links', $this->_linksModel->getLinks($user['email']));
            $this->setVar('stats', $this->_linksModel->getClicksCount($user['email']));
            if ($this->_code) {
                $this->setVar('codeView', $this->_code);
            }
        }
    }
}