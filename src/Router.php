<?php

    /**
     * bulb
     *
     * The Garden (@_WeAreTheGarden)
     * wearethegarden.co.uk
     * github.com/wearethegaden
     */

    namespace Bulb;

    class Router {

        public $app;
        protected $routes;

        public $route;
        public $directory;

        public function __construct($app) {
            $this->app = $app;
            $this->routes = (object) [];

            // Grab the current route.
            $root = preg_quote($_SERVER['DOCUMENT_ROOT'], '/');
            $this->directory = parse_url(preg_replace("/^{$root}/", '', $this->app->path), PHP_URL_PATH);
            $this->route = (object) parse_url($_SERVER['REQUEST_URI']);

            // Separate the query string parameters into key => value accessible attributes.
            parse_str($this->route->query, $this->route->query);
            $this->route->query = (object) $this->route->query;

            // Remove directory from route?
            if (!empty($this->directory) && strpos($this->route->path, $this->directory) === 0)
                $this->route->path = substr($this->route->path, strlen($this->directory));
        }

        /**
         * Add a route to the routing table.
         *
         * @param string $destination The route URL.
         * @param array $params       Route parameters (Namespace, controller, action etc.)
         */
        public function add($destination, $method, $params = []) {
            $this->destination = $destination;
            $this->method = strtolower($method);

            if(!$this->routes->{$this->destination})
                $this->routes->{$this->destination} = (object) [];

            $this->routes->{$this->destination}->{$this->method} = (object) $params;
        }

        /**
         * Grab the controller and action for the current route, and return
         * the relevant function.
         */
        public function dispatch() {

            /**
             * Check that the current route is in our routing table. If it is, construct
             * the controller and initiate it.
             */
            if(isset($this->routes->{$this->route->path})) {
                $this->serverMethod = strtolower($_SERVER['REQUEST_METHOD']);

                foreach($this->routes as $route => $methods) {

                    foreach($methods as $method => $params) {
                        $this->method = $method;
                        $this->params = $params;

                        $this->controller = "\\{$this->params->namespace}\\{$this->params->controller}Controller";
                        $this->controller = new $this->controller($this->app, $this->route);
                        $this->action = $this->params->action;

                        if($this->route->path == $route && $this->serverMethod == $this->method)
                            return $this->controller->{$this->action}();
                    }
                }

            /**
             * If the route doesn't exist in our routing table, return a 404 page.
             */
            } else {
                $this->controller = new \Bulb\Controller($this->app);
                return $this->controller->error(404);
            }
        }
    }
