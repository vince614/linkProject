<?php
namespace Model;
use http\QueryString;
use Model\Core\Database;
use PDO;

/**
 * Class Users
 */
class Users extends Database
{

    /**
     * Config user regiration
     */
    const MIN_PASSWORD_LENGTH = 7;
    const CODE_USER_AUTH = "form";

    /**
     * Errors
     * @var array
     */
    protected $_errors = [];

    /**
     * Register user
     *
     * @param $username
     * @param $email
     * @param $password
     * @param $confirmPassword
     * @param string $auth
     * @return bool
     */
    public function register($username, $email, $password, $confirmPassword, $auth = self::CODE_USER_AUTH)
    {
        // Secure mode when register with form
        $secureMode = $auth == self::CODE_USER_AUTH;
        if ($password === $confirmPassword || !$secureMode) {
            if (strlen($password) >= self::MIN_PASSWORD_LENGTH || !$secureMode) {
                if (!$this->getUserByEmail($email)) {
                    $req = Database::getPDO()->prepare("INSERT INTO users (username, password, email, auth, date_time) VALUES (?, ?, ?, ?, ?)");
                    $req->execute(array($username, sha1($password), $email, $auth, time()));
                    return true;
                }
                $this->_addError("Cette adresse mail existe déjà");
                return false;
            }
            $this->_addError(sprintf("Votre mot de passe dois faire plus de %d caractères", self::MIN_PASSWORD_LENGTH - 1));
            return false;
        }
        $this->_addError("Veuillez entrez deux mots de passe identique");
        return false;
    }

    /**
     * Login user
     *
     * @param $email
     * @param $password
     * @return bool
     */
    public function login($email, $password)
    {
        if ($this->_validateEmail($email)) {
            if ($this->getUserByEmail($email)) {
                $req = Database::getPDO()->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
                $req->execute(array($email, sha1($password)));
                if ($req->rowCount() > 0) {
                    // Set user session
                    $userInformation = $req->fetch();
                    Database::_setLogin($userInformation);
                    return $userInformation;
                }
                $this->_addError("Le mot de passe n'est pas correct. Veuillez réessayer");
                return false;
            }
            $this->_addError("Aucun compte n'existe avec cette adresse mail");
            return false;
        }
        $this->_addError("Merci de saisir une adresse mail valide");
        return false;
    }

    /**
     * Get User
     *
     * @param $userId
     * @return bool|array
     */
    public function getUser($userId)
    {
        $req = Database::getPDO()->prepare("SELECT * FROM users WHERE id = ?");
        $req->execute(array((int)$userId));
        if ($req->rowCount() > 0) {
            return $req->fetch();
        }
        return false;
    }

    /**
     * Get user by email
     *
     * @param $email
     * @return bool|mixed
     */
    public function getUserByEmail($email)
    {
        $req = Database::getPDO()->prepare("SELECT * FROM users WHERE email = ?");
        $req->execute(array((string)$email));
        if ($req->rowCount() > 0) {
            return $req->fetch();
        }
        return false;
    }

    /**
     * Delete user by ID
     *
     * @param $userId
     * @return bool
     */
    public function deleteUser($userId)
    {
        $req = Database::getPDO()->prepare("DELETE FROM users WHERE id = ?");
        $req->execute(array($userId));
        if ($req->rowCount() > 0) {
            // Destroy session
            unset($_SESSION['user']);
            setcookie('login', null);
            setcookie('pass_hache', null);
            setcookie('remember_key', null, null, '/');

            return true;
        }
        return false;
    }

    /**
     * Change password
     *
     * @param $userId
     * @param $oldpassword
     * @param $newPassword
     * @param $newPasswordVerif
     * @return bool
     */
    public function changePassword($userId, $oldpassword, $newPassword, $newPasswordVerif)
    {
        $user = $this->getUser($userId);
        if ($user) {
            if (sha1($oldpassword) == $user['password']) {
                if (strlen($newPassword) >= self::MIN_PASSWORD_LENGTH) {
                    if ($newPassword == $newPasswordVerif) {
                        $req = Database::getPDO()->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $req->execute(array(sha1($newPassword), $userId));
                        return true;
                    }
                    $this->_addError("Password don't match");
                    return false;
                }
                $this->_addError(sprintf("Votre mot de passe dois faire plus de %d caractères", self::MIN_PASSWORD_LENGTH - 1));
                return false;
            }
            $this->_addError("Password is incorrect");
            return false;
        }
        $this->_addError("User not found");
        return false;
    }

    /**
     * Change username
     *
     * @param $userId
     * @param $username
     * @return bool
     */
    public function changeUsername($userId, $username)
    {
        $user = $this->getUser($userId);
        if ($user) {
            $req = Database::getPDO()->prepare("UPDATE users SET username = ? WHERE id = ?");
            $req->execute(array($username, $userId));
            $this->_refreshSession($userId);
            return true;
        }
        $this->_addError("User not found");
        return false;
    }

    /**
     * Refresh session
     *
     * @param $userId
     */
    private function _refreshSession($userId)
    {
        $user = $this->getUser($userId);
        if ($user) {
            $this->_setLogin($user);
        }
    }

    /**
     * Get all users errors
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Validate email
     *
     * @param $email
     * @return mixed
     */
    public function _validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
    }

    /**
     * Add error message
     * @param $error
     */
    private function _addError($error)
    {
        $this->_errors[] = $error;
    }

    /**
     * Set remember key
     *
     * @param $userId
     * @return bool
     */
    public function setRememberKey($userId)
    {
        /** @var PDO $pdo */
        $pdo = Database::getPDO();

        // Delete all old keys
        $req = $pdo->prepare("DELETE FROM remember_me WHERE user_id = ?");
        $req->execute(array($userId));

        while (true) {

            // Generate key
            $key = $this->_generateRememberKey(30);

            // Check if key exist
            $req = $pdo->prepare("SELECT * FROM remember_me WHERE remember_key = ?");
            $req->execute(array($key));
            if ($req->rowCount() == 0) {

                // Insert key in database
                $req = $pdo->prepare("INSERT INTO remember_me (user_id, remember_key) VALUES (?, ?)");
                $req->execute(array($userId, $key));

                // Set cookie for 30 days
                $cookieDaysExpire = 30;
                setcookie('remember_key', $key, time() + 3600 * 24 * $cookieDaysExpire, '/');

                // Stop loop
                return true;
            }
        }
    }

    /**
     * Generate random key
     *
     * @param $lenght
     * @return string
     */
    protected function _generateRememberKey($lenght)
    {
        $randomPseudoBytes = openssl_random_pseudo_bytes($lenght);
        return base64_encode($randomPseudoBytes);
    }

}