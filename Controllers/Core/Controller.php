<?php
namespace Controllers\Core;

/**
 * Class Controller
 * @package Controllers\Core
 */
class Controller
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
        return isset($_SESSION['username']) && !empty($_SESSION['username']);
    }

}