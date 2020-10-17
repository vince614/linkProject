<?php

namespace Controllers;

use Controllers\Core\Controller;
use Exception;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Model\Core\Database;
use Model\Users;
use Wohali\OAuth2\Client\Provider\Discord;

/**
 * Class LoginDiscordController
 * @package Controllers
 */
class LoginDiscordController extends Controller
{

    /**
     * Login auth code
     */
    const LOGIN_AUTH_CODE = "discord";

    /**
     * Models path
     */
    const USERS_MODEL_PATH = "Models/Users.php";

    /**
     * OAuth2 Discord settings
     * @var string
     */
    private $_clientId = "636565800379219989";
    private $_clientSecret = "YLIevJgW5z5ggqhQAIcHlbRN2PQF0WZC";
    private $_redirectUri = "/loginDiscord";
    private $_options = [
        'scope' =>
            ['identify', 'email']
    ];

    /**
     * Provider
     * @var Discord
     */
    private $_provider;

    /**
     * Model instance
     * @var Users
     */
    private $_usersModel;

    /**
     * LoginDiscordController constructor.
     * @throws IdentityProviderException
     */
    public function __construct()
    {
        // Check if is login
        if ($this->isLogin()) header("Location: ./dashboard");
        parent::__construct();
        $this->_provider = new Discord([
            'clientId' => $this->_clientId,
            'clientSecret' => $this->_clientSecret,
            'redirectUri' => $this->getHost() . $this->_redirectUri
        ]);
        $this->_initModels();
        $this->_Oauth2Connection();
    }

    private function _initModels()
    {
        require_once self::USERS_MODEL_PATH;
        $this->_usersModel = new Users();
    }

    /**
     * Oauth 2 connection
     *
     * @throws IdentityProviderException
     */
    private function _Oauth2Connection()
    {
        if (!isset($_GET['code'])) {
            // Step 1. Get authorization code
            $authUrl = $this->_provider->getAuthorizationUrl($this->_options);
            $_SESSION['oauth2state'] = $this->_provider->getState();
            header('Location: ' . $authUrl);
        } else {
            if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
                // Check given state against previously stored one to mitigate CSRF attack
                unset($_SESSION['oauth2state']);
                exit('Invalid state');
            } else {
                // Step 2. Get an access token using the provided authorization code
                $code = $_GET['code'];
                $token = $this->_provider->getAccessToken('authorization_code', [
                    'code' => $code
                ]);

                // Step 3. (Optional) Look up the user's profile with the provided token
                try {
                    $user = $this->_provider->getResourceOwner($token);

                    // Get user detail
                    $userInfos = [
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'username' => $user->getUsername(),
                        'avatar' => "https://cdn.discordapp.com/avatars/" . $user->getId() . "/" . $user->getAvatarHash(),
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
                        $userInfos['date_time'] = $user['date_time'];
                    }
                    Database::_setLogin($userInfos);
                    header('Location: ./dashboard');
                } catch (Exception $e) {
                    // Failed to get user details
                    exit('Oh dear...');
                }
            }
        }
    }
}