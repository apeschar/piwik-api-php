<?php

/**
 * @author Albert Peschar <albert@peschar.net>
 */

class PiwikAPI {
    protected $_url;
    protected $_token_auth;

    public function __construct($url, $token_auth) {
        $this->_url = $url;
        $this->_token_auth = $token_auth;
    }

    public function __get($plugin) {
        return $this->plugin($plugin);
    }

    public function plugin($plugin) {
        return new PiwikAPIPlugin($this, $plugin);
    }

    public function call($method, array $arguments = array()) {
        $query = array_merge(array(
            'module'        => 'API',
            'method'        => $method,
            'token_auth'    => $this->_token_auth,
            'format'        => 'JSON',
        ), $arguments);
        $query_string = http_build_query($query);
        $url = $this->_url . '/?' . $query_string;
        
        $response = @file_get_contents($url);
        if(!$response)
            throw new PiwikAPIError('PiwikAPI: no response received from API');
        $data = @json_decode($response, true);
        if(!is_array($data))
            throw new PiwikAPIError('PiwikAPI: no valid JSON was received');

        return $data;
    }
}

class PiwikAPIPlugin {
    protected $_api;
    protected $_plugin;

    public function __construct(PiwikAPI $api, $plugin) {
        $this->_api = $api;
        $this->_plugin = $plugin;
    }

    public function __call($method, array $arguments) {
        $full_method = $this->_plugin . '.' . $method;
        if(sizeof($arguments) != 1 || !is_array($arguments[0]))
            throw new Exception('PiwikAPIPlugin: expected array');
        return $this->_api->call($full_method, $arguments[0]);
    }
}

class PiwikAPIError extends Exception {
}
