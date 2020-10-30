<?php
namespace General;
use Model\Core\Database;
use PDO;

/**
 * Require class Database
 */
require_once __DIR__ . '/Models/Core/Database.php';

/**
 * Class General
 * @package General
 */
class General extends Database
{

    /**
     * Host (domain)
     * @var string
     */
    private $_host;

    /**
     * Get url with GET_METHOD
     *
     * @return string
     */
    public function getUrl()
    {
        if (isset($_GET['url'])) {
            return $_GET['url'];
        }
        return '/';
    }

    /**
     * Set host with config table
     */
    private function _initHost()
    {
        parent::__construct();
        $req = parent::getPDO()->prepare("SELECT * FROM config");
        $req->execute();
        $config = $req->fetchAll();
        $this->_host =  $config[1]['value'] != NULL ? $config[1]['value'] : $config[0]['value'];
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        if (!$this->_host) {
            $this->_initHost();
        }
        return $this->_host;
    }

    /**
     * Try to connect with cookie
     */
    public function tryConnectionWithCookie()
    {
        if (isset($_COOKIE['remember_key'])
            && !empty($_COOKIE['remember_key'])) {

            /** @var PDO $pdo */
            $pdo = Database::getPDO();

            // Get remember key
            $rememberKey = $_COOKIE['remember_key'];

            $req = $pdo->prepare("SELECT * FROM remember_me WHERE remember_key = ?");
            $req->execute(array($rememberKey));
            if ($req->rowCount() > 0) {

                // Get user ID
                $result = $req->fetch();
                $userId = $result['user_id'];

                // Get user information
                $req = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $req->execute(array($userId));
                if ($req->rowCount() > 0) {
                    // Set login
                    $user = $req->fetch();
                    Database::_setLogin($user);
                }
            }
        }
    }

}