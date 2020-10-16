<?php
namespace Model\Core;
use PDO;

/**
 * Class Database
 * @package Core
 */
class Database
{
    /**
     * PDO instancie
     * @var PDO
     */
    protected $_pdo;

    /**
     * Params to connect with PDO to database
     * @var string
     */
    protected $_hostname = "localhost";
    protected $_database = "clypy";
    protected $_user = "root";
    protected $_password = "";

    /**
     * Core_Mysql constructor.
     */
    public function __construct()
    {
        if (!$this->_pdo) {
            $this->_pdo = new PDO("mysql:host={$this->_hostname};dbname={$this->_database}", $this->_user, $this->_password);
        }
    }

    /**
     * Get PDO instancie
     * @return PDO
     */
    public function getPDO()
    {
        return $this->_pdo;
    }
}