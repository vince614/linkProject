<?php
namespace Router;

/**
 * Class Route
 * @package Router
 */
class Route
{

    /**
     * Path
     * @var string
     */
    private $path;

    /**
     * Callable
     * @var mixed
     */
    private $callable;

    /**
     * Matches
     * @var array
     */
    private $matches = [];

    /**
     * Params
     * @var array
     */
    private $params = [];

    /**
     * Route constructor.
     * @param $path
     * @param $callable
     */
    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');  // On retire les / inutils
        $this->callable = $callable;
    }

    /**
     * Permettra de capturer l'url avec les paramètre
     * get('/posts/:slug-:id') par exemple
     * @param $url
     * @return bool
     */
    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * Call route
     * @return mixed
     */
    public function call()
    {
        $this->load();
        return call_user_func_array($this->callable, $this->matches);
    }

    /**
     * Load model & controller
     */
    private function load()
    {
        require_once 'Models/Core/Database.php';

        // Index controller
        if ($this->path === "") {
            $this->path = "index";
        }

        $controller = ucfirst($this->path);

        $controllerNamespace = "Controllers\\";
        $controllerClass = $controller . "Controller";
        $controllerFile = "Controllers/" . $controllerClass . '.php';
        $controllerClassWithNamespace = $controllerNamespace . $controllerClass;

        // If controller exist
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            new $controllerClassWithNamespace($this->path);
        }
    }
}