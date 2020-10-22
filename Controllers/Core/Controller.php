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
     * View
     * @var string
     */
    private $_view;

    /**
     * Display HTML content on the view
     *
     * @param $view
     * @return false|string
     */
    protected function render($view)
    {
        $this->_view = $view;
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

    /**
     * Translates HTML5 elements.
     *
     * @param $html
     * @param null $var
     * @return array
     */
    public function __($html, $var = null)
    {
        // Current locale
        $locale = 'en';
        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            $locale = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
        }

        // Get locale
        if (isset($_SESSION['locale'])) {
            $locale = $_SESSION['locale'];
        }
        if (isset($_GET['locale'])) {
            // Get locale
            $locale = $_GET['locale'];
            // Put in session
            $_SESSION['locale'] = $locale;
        }

        // Return default language
        if ($var !== null && $locale === "en") {
            return str_replace('%', $var, $html);
        }
        if ($locale === "en") {
            return $html;
        }

        // Return if file don't exist
        $file = "locale/" . $locale . "/" . $this->_view . ".csv";
        if (!file_exists($file)) {
            return $html;
        }

        // Open file and return the translation
        if (($handle = fopen($file, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",", '"')) !== false) {
                if ($var === null) {
                    if ($data[0] === $html) {
                        return $data[1];
                    }
                } else {
                    $text = explode('%', $html);
                    if (strpos($data[0], $text[0]) !== false && strpos($data[0], $text[1]) !== false) {
                        return str_replace('%', $var, $data[1]);
                    }
                }
            }
            return $html;
        }
        return $html;
    }

}