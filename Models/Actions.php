<?php
namespace Model;
use Model\Core\Database;

/**
 * Class Actions
 * @package Model
 */
class Actions extends Database
{
    /**
     * Actions constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->_process();
    }

    private function _process()
    {
        if ($request = $_POST) {
            var_dump($request); exit;
        }
    }
}