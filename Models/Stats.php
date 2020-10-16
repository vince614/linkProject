<?php
namespace Model;
use Model\Core\Database;

/**
 * Class Stats
 * @package Model
 */
class Stats extends Database
{

    /**
     * Count links
     *
     * @return int
     */
    public function getLinksCount()
    {
        $req = Database::getPDO()->query("SELECT * FROM links_table");
        return $req->rowCount();
    }

    /**
     * Count users
     *
     * @return int
     */
    public function getUsersCount()
    {
        $req = Database::getPDO()->query("SELECT * FROM account");
        return $req->rowCount();
    }

    /**
     * Count clicks
     *
     * @return int
     */
    public function getClicksCount()
    {
        $req = Database::getPDO()->query("SELECT * FROM clicks");
        return $req->rowCount();
    }

}