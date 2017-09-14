<?php

    /**
     * bulb
     *
     * The Garden (@_WeAreTheGarden)
     * wearethegarden.co.uk
     * github.com/wearethegaden
     */

    namespace Bulb;

    class Controller {

        public $app;

        public function __construct($app) {
            $this->app = $app;
        }

        /**
         * Return an error view based on the provided error code.
         * Also sets the http_response_code based on the error code.
         *
         * @param int $code Error code (i.e. 404).
         *
         */
         public function error($code) {
            $this->code = $code;
            $this->status = http_response_code($this->code);
            $this->status = http_response_code();

            $this->view = new View($this->app);
            $this->view->render("Error/{$this->code}", ['status' => $this->status]);
        }
    }
