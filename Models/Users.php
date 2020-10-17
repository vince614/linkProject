<?php
namespace Model;
use Model\Core\Database;

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
                    $req->execute(array((string) $username, (string) sha1($password), (string) $email, $auth, time()));
                    return false;
                }
                $this->_addError("Cette adresse mail existe déjà");
                return false;
            }
            $this->_addError("Votre mot de passe dois faire plus de " . self::MIN_PASSWORD_LENGTH - 1 . " caractères");
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
                    return true;
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
     * Delete user by user id
     *
     * @param $userId
     * @return bool
     */
    public function deleteUser($userId)
    {
        $req = Database::getPDO()->prepare("DELETE FROM users WHERE id = ?");
        $req->execute(array($userId));
        if ($req->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Edit user username &/or password
     *
     * @param $userId
     * @param null $username
     * @param null $password
     * @return bool
     */
    public function editUser($userId, $username = null, $password = null)
    {
        // If don't have informations return statement false
        if (!$username && !$password) {
            return false;
        }

        // Initialize datas & query
        $query = "";
        $datas = [(int)$userId];

        if ($username) {
            $query .= "SET username = ?";
            $datas[] = (string) $username;
        }
        if ($password) {
            if ($username) {
                $query .= " AND ";
            }
            $query .= "SET password = ?";
            $datas[] = (string) $password;
        }

        // Send request
        $req = Database::getPDO()->prepare("UPDATE users {$query} WHERE id = ?");
        $req->execute($datas);
        if ($req->rowCount() > 0) {
            return true;
        }
        $this->_errors[] = "Un error was expected. User isn't editable."; //@TODO Faire une meilleur erreur
        return false;
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

}