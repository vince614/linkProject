<?php
namespace Controllers\Core;
use General\General;

/**
 * Class Controller
 * @package Controllers\Core
 */
class Controller extends General
{

    /**
     * Variables
     * @var $vars array
     */
    public $vars = [];

    /**
     * Display HTML content on the view
     *
     * @param $view
     * @return false|string
     */
    protected function render($view)
    {
        // Get session message
        if ($message = $this->getSessionMessage()) {
            $this->setVar('sessionMessage', $message['msg']);
            if ($redirect = $message['redirect']) {
                $this->setVar('sessionRedirect', $redirect);
            }
        }

        extract($this->vars);

        /**
         * Include template views
         */
        $viewFile = 'Views/' . str_replace('.', '/', $view) . '.phtml';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            $this->notFound();
        }

        /**
         * Include end of file
         */
        require 'Views/script/script.phtml';
    }

    /**
     * Set variables
     *
     * @param $index
     * @param $value
     * @return mixed
     */
    public function setVar($index, $value)
    {
        return $this->vars[$index] = $value;
    }

    /**
     * Redirect if page not found
     */
    public function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        return die('La page est introuvable');
    }

    /**
     * Redirect if don't have accès
     */
    public function forbidden()
    {
        header('HTTP/1.0 403 Forbidden');
        die('Accès interdit');
    }

    /**
     * Check if user is login
     *
     * @return bool
     */
    public function isLogin()
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    /**
     * Get Post
     *
     * @return mixed
     */
    public function getPost()
    {
        return $_POST;
    }

    /**
     * Add session message & redirect
     *
     * @param $msg
     * @param $redirect
     */
    protected function _addSessionMessage($msg, $redirect = false)
    {
        $_SESSION['message'] = [
            'msg' => $msg,
            'redirect' => $redirect
        ];
    }

    /**
     * Get session message
     *
     * @return bool|mixed
     */
    public function getSessionMessage()
    {
        if (isset($_SESSION['message'])
            && !empty($_SESSION['message'])) {

            $msg = $_SESSION['message'];
            unset($_SESSION['message']);

            return $msg;
        }
        return false;
    }

    /**
     * Get user logged
     *
     * @return mixed
     */
    public function getUserLogged()
    {
        if ($this->isLogin()) {
            // If user don't have avatar set default avatar
            if ($_SESSION['user']['avatar'] === null) {
                $_SESSION['user']['avatar'] = $this->getHost() . "/assets/img/undraw_profile_pic_ic5t.svg";
            }
            return $_SESSION['user'];
        }
    }

}