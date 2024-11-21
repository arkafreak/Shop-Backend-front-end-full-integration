<?php
class Core
{
    protected $currentController = 'Pages'; // Default controller
    protected $currentMethod = 'index';      // Default method
    protected $params = [];                   // Parameters

    public function __construct()
    {
        $url = $this->getUrl();

        // Check if the URL has a controller
        if (!empty($url) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
            $this->currentController = ucwords($url[0]);
            unset($url[0]); // Remove controller from URL
        }

        // Require the controller file and instantiate it
        require_once '../app/controllers/' . $this->currentController . '.php';
        $this->currentController = new $this->currentController;

        // Check if the method exists in the controller
        if (isset($url[1]) && method_exists($this->currentController, $url[1])) {
            $this->currentMethod = $url[1];
            unset($url[1]); // Remove method from URL
        }

        // Get the parameters after controller and method
        $this->params = $url ? array_values($url) : [];

        // Call the method with parameters, if any
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/'); // Remove trailing slash
            $url = filter_var($url, FILTER_SANITIZE_URL); // Sanitize the URL
            $url = explode('/', $url); // Split URL into parts
            return $url;
        }
        return []; // Return an empty array if no URL is set
    }
}
