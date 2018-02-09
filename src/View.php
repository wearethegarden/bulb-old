<?php

    /**
     * bulb
     *
     * The Garden (@_WeAreTheGarden)
     * wearethegarden.co.uk
     * github.com/wearethegaden
     */

    namespace Bulb;

    class View {

        public $app;
        public $twig;

        public $cache;

        public function __construct($app) {
            $this->app = $app;
            $this->cache = false;

            // Is cache enabled?
            if($this->app->cache->enabled == true) {

                // Does our cache directory exists and is it writable?
                if(!file_exists($this->app->cache->path)) {
                    mkdir($this->app->cache->path, 0777);
                } else {
                    if(!is_writable($this->app->cache->path)) {
                        throw new \Exception("Cache directory ({$this->app->cache->path}) is not writable. Please change folder permissions to 0777.");
                    } else {
                        $this->cache = $this->app->cache->path;
                    }
                }
            }

            $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem("{$this->app->path}/Views"), array('cache' => $this->cache, 'auto_reload' => true));
        }

        /**
         * Render the provided view.
         *
         * @param string $view The view to render.
         * @param array $args  Array of arguments to pass to the view.
         */
        public function render($view, $args = []) {
            $this->view = $view;
            $this->args = $args;

            echo $this->twig->render("{$this->view}.php", $this->args);
        }
    }
