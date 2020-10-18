<?php
namespace Model;
use Model\Core\Database;

/**
 * Class Code
 * @package Model
 */
class Code extends Database
{

    /**
     * Code
     * @var string
     */
    private $_code;

    /**
     * Code constructor.
     * @param $code
     */
    public function __construct($code)
    {
        if (!$code) {
            return;
        }
        $this->_code = $code;
        parent::__construct();
    }

}