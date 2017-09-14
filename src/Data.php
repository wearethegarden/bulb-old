<?php

    /**
     * bulb
     *
     * The Garden (@_WeAreTheGarden)
     * wearethegarden.co.uk
     * github.com/wearethegaden
     */

    namespace Bulb;

    class Data {

        public $app;

        public function __construct($app) {
            $this->app = $app;
        }

        /**
         * Retreive the data from a specified JSON file, and return it.
         *
         * @param string $filename Filename of the JSON file.
         * @param string $schema   Schema of the data within the JSON file.
         *
         * @return object
         */
        public function get($filename, $schema = null) {
            $this->filename = $filename;
            $this->schema = $schema;

            $this->data = '';
            $this->items = json_decode(file_get_contents("{$this->app->path}/inc/@data/{$this->filename}.json"), false);

            if($this->schema && isset($this->items->{$this->schema})) {
                $this->data = $this->items->{$this->schema};
            } else {
                $this->data = $this->items;
            }

            return $this->data;
        }
    }
