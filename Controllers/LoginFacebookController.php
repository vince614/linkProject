<?php
namespace Controllers;
use Controllers\Core\Controller;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Model\Core\Database;
use Model\Users;

/**
 * Class loginFacebookController
 * @package Controllers
 */
class loginFacebookController extends Controller
{

    /**
     * Login auth code
     */
    const LOGIN_AUTH_CODE = "facebook";

    /**
     * Models path
     */
    const USERS_MODEL_PATH = "Models/Users.php";

    /**
     * OAuth2 Facebook settings
     * @var string
     */
    private $_appId = "727282361017648";
    private $_appSecret = "680db8577fe4cf94c9fd584fdf285a97";
    private $_defaultGraphVersion = "v2.10";
    private $_redirectUri = "/loginFacebook";
    private $_permissions = ['email'];

    /**
     * Facebook client
     * @var Facebook
     */
    private $_facebookClient;

    /**
     * Model instance
     * @var Users
     */
    private $_usersModel;

    /**
     * loginFacebookController constructor.
     * @throws FacebookSDKException
     */
    public function __construct()
    {
        // Check if is login
        if ($this->isLogin()) {
            header("Location: ./dashboard");
        }
        parent::__construct();
        // Step 1: Enter Credentials
        $this->_facebookClient = new Facebook([
            'app_id' => $this->_appId,
            'app_secret' => $this->_appSecret,
            'default_graph_version' => $this->_defaultGraphVersion
        ]);
        $this->_initModels();
        $this->_Oauth2Connection();
    }

    /**
     * Oauth2 Facebook Connection
     */
    private function _Oauth2Connection()
    {
        // Step 2 Create the url
        if (empty($access_token)) {
            $url = $this->_facebookClient->getRedirectLoginHelper()->getLoginUrl(
                $this->getHost() . $this->_redirectUri,
                $this->_permissions
            );
        }

        // Step 3 : Get Access Token
        try {
            $access_token = $this->_facebookClient->getRedirectLoginHelper()->getAccessToken();
        } catch (FacebookSDKException $e) {
            echo $e->getMessage();
        }

        // Step 4: Get the graph user
        if (isset($access_token)) {
            try {
                $response = $this->_facebookClient->get('/me?fields=first_name,email', $access_token);
                $fb_user = $response->getGraphUser();
                if (isset($fb_user['first_name'], $fb_user['email'])) {
                    if (!empty($fb_user['first_name']) AND !empty($fb_user['email'])) {

                        $userInfos = [
                            'id' => 0,
                            'email' => $fb_user['email'],
                            'username' => $fb_user['first_name'],
                            'date_time' => time()
                        ];

                        // Check if user exist
                        $user = $this->_usersModel->getUserByEmail($userInfos['email']);
                        if (!$user) {
                            // Register user
                            $this->_usersModel->register(
                                $userInfos['username'],
                                $userInfos['email'],
                                null,
                                null,
                                self::LOGIN_AUTH_CODE
                            );
                        } else {
                            $userInfos['id'] = $user['id'];
                            $userInfos['date_time'] = $user['date_time'];
                        }
                        Database::_setLogin($userInfos);
                        header('Location: ./dashboard');
                    }
                }
            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                echo  'Graph returned an error: ' . $e->getMessage();
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
            }
        } else {
            header("Location: " . $url);
        }
    }

    /**
     * Init model
     */
    private function _initModels()
    {
        require_once self::USERS_MODEL_PATH;
        $this->_usersModel = new Users();
    }

}