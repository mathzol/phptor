<?php
/**
 * @author Máthé Zoltán <mathzoltan@gmail.com>
 * @licence The MIT License (MIT)
 *
 * Copyright (c) 2013 Mathe Zoltan
 */

class PHPTor {
    public $control_port = 9051;
    public $port = 9050;
    public $host = '127.0.0.1';
    public $torConnected = false;
    private $curl = null;

    function __construct()
    {
        self::initConnection();
    }

    function __destruct()
    {
        self::closeConnection();
    }

    private function closeConnection()
    {
        if ($this->curl !== null) curl_close($this->curl);
    }

    private function initConnection()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_TRANSFERTEXT, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, true);
    }

    public function torConnection()
    {
        $this->torConnected = true;
        self::closeConnection();
        self::initConnection();
        curl_setopt($this->curl, CURLOPT_PROXY, $this->host);
        curl_setopt($this->curl, CURLOPT_PROXYPORT, $this->port);
        curl_setopt($this->curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }

    public function torDisconnection()
    {
        $this->torConnected = false;
        self::closeConnection();
        self::initConnection();
    }

    public function newIdentity()
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (socket_connect($socket, $this->host, $this->control_port))
        {
            socket_send($socket, "AUTHENTICATE\r\n", 100, MSG_EOF);

            $response = '';
            socket_recv($socket, $response, 20, MSG_PEEK);

            if (substr($response, 0, 3) == "250")
            {
                socket_send($socket, "SIGNAL NEWNYM\r\n", 100, MSG_EOF);
                socket_close($socket);

                self::torDisconnection();
                self::torConnection();
                return 0;
            }

            return 1;
        } else
            return 1;
    }

    public function request($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        return curl_exec($this->curl);
    }
}