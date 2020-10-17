<?php

namespace Controllers;

use Controllers\Core\Controller;
use Exception;
use Google_Client;
use Model\Core\Database;
use Model\Users;

/**
 * Class LoginGoogleController
 * @package Controllers
 */
class LoginGoogleController extends Controller
{
    /**
     * Login auth code
     */
    const LOGIN_AUTH_CODE = "google";

    /**
     * Models path
     */
    const USERS_MODEL_PATH = "Models/Users.php";

    /**
     * OAuth2 Google settings
     * @var string
     */
    private $_clientId = "729851811799-nqrou1qcmitqo1jplclc9tkobp0omvn8.apps.googleusercontent.com";
    private $_clientSecret = "9V06LyUF1uvvxcnyO4T9TPQz";
    private $_redirectUri = "/loginGoogle";

    /**
     * Scopes
     * @var array
     */
    private $_scopes = [
        'email',
        'profile'
    ];

    /**
     * Google client
     * @var Google_Client
     */
    private $_googleClient;


    /**
     * Model instance
     * @var Users
     */
    private $_usersModel;

    /**
     * LoginGoogleController constructor.
     */
    public function __construct()
    {
        // Check if is login
        if ($this->isLogin()) {
            header("Location: ./dashboard");
        }
        parent::__construct();

        //Step 1: Enter you google account credentials
        $this->_googleClient = new Google_Client();
        $this->_googleClient->setClientId($this->_clientId);
        $this->_googleClient->setClientSecret($this->_clientSecret);
        $this->_googleClient->setRedirectUri($this->getHost() . $this->_redirectUri);
        $this->_googleClient->setScopes($this->_scopes);
        $this->_initModels();
        $this->_Oauth2Connection();
    }

    /**
     * Oauth2 Google Connection
     */
    private function _Oauth2Connection()
    {
        //Step 2 : Create the url
        $authUrl = $this->_googleClient->createAuthUrl();

        //Step 3 : Get the authorization  code
        $code = isset($_GET['code']) ? $_GET['code'] : null;

        //Step 4: Get access token
        if (isset($code)) {
            try {
                $token = $this->_googleClient->fetchAccessTokenWithAuthCode($code);
                $this->_googleClient->setAccessToken($token);
            } catch (Exception $e){
                echo $e->getMessage();
            }
            try {
                $payLoad = $this->_googleClient->verifyIdToken();
            }catch (Exception $e) {
                echo $e->getMessage();
            }
        } else{
            $payLoad = null;
        }

        // If get payLoad
        if (isset($payLoad)){
            if (isset($payLoad['email'], $payLoad['given_name'], $payLoad['picture'])) {
                if (!empty($payLoad['email']) AND !empty($payLoad['given_name']) AND !empty($payLoad['picture'])) {

                    $userInfos = [
                        'id' => 0,
                        'email' => $payLoad['email'],
                        'username' => $payLoad['given_name'],
                        'avatar' => $payLoad['picture'],
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
        } else {
            header('Location: ' . $authUrl);
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