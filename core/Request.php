<?php


namespace app\core;


class Request
{

    public function getPath()
    {

//        we can assume that if the request_uri is not there, the path will be the main domain.
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        // check if query '?' is presented. We need to search the question mark and locate its position.
        $position = strpos($path, '?');
        // this will return false if there is no question mark
        if ($position === false) {
            return $path;
        }
        // this will return the path in case we have params in the URL
        // will take the position of the '?' as last character and begin in 0.
        return substr($path, 0, $position);
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return $this->method() === 'get';
    }

    public function isPost()
    {
        return $this->method() === 'post';
    }

    public function getBody()
    {
        $body = [];

        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {

                // this will look in the super global GET and check using the key, take the value and sanitize it from invalid chars.
                 $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->method() === 'post') {
            foreach ($_POST as $key => $value) {

                // this will look in the super global POST and check using the key, take the value and sanitize it from invalid chars.
                 $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

}