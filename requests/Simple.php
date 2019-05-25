<?php

namespace Yaf\Request;

use Yaf\Request_Abstract;

/**
 * @link http://www.php.net/manual/en/class.yaf-request-simple.php
 */
class Simple extends Request_Abstract
{
    /**
     * @link http://www.php.net/manual/en/yaf-request-simple.construct.php
     *
     * @param string|null $method
     * @param string|null $module
     * @param string|null $controller
     * @param string|null $action
     * @param array|null $params
     */
    public function __construct($method = null, $module = null, $controller = null, $action = null, array $params = null)
    {
        $this->instance($module, $controller, $action, $method, $params);
    }

    /**
     * @link http://www.php.net/manual/en/yaf-request-simple.getquery.php
     *
     * @param string $name
     * @param mixed $default
     * @return null|string
     */
    public function getQuery(string $name = null, $default = null)
    {
        if (null === $name) {
            return $_GET;
        }

        return isset($_GET[$name]) ? $_GET[$name] : $default;
    }

    /**
     * @link http://www.php.net/manual/en/yaf-request-simple.getrequest.php
     *
     * @param string $name
     * @param null|mixed $default
     * @return null|string
     */
    public function getRequest(string $name = null, $default = null): ?string
    {
        if (null === $name) {
            return $_REQUEST;
        }

        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
    }

    /**
     * @link http://www.php.net/manual/en/yaf-request-simple.getpost.php
     *
     * @param string $name
     * @param null|mixed $default
     * @return null|string
     */
    public function getPost(string $name = null, $default = null): ?string
    {
        if (null === $name) {
            return $_POST;
        }

        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    /**
     * @link http://www.php.net/manual/en/yaf-request-simple.getcookie.php
     *
     * @param string $name
     * @param null|mixed $default
     * @return null|string
     */
    public function getCookie(string $name = null, $default = null)
    {
        if (null === $name) {
            return $_COOKIE;
        }

        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }

    /**
     * @link http://www.php.net/manual/en/yaf-request-simple.getfiles.php
     *
     * @param string $name
     * @param null|mixed $default
     * @return null|string
     */
    public function getFiles(string $name = null, $default = null): ?string
    {
        if (null === $name) {
            return $_FILES;
        }

        return isset($_FILES[$name]) ? $_FILES[$name] : $default;
    }

    /**
     * @link http://www.php.net/manual/en/yaf-request-simple.get.php
     *
     * @param string $name
     * @param null|mixed $default
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        $value = $this->_params[$name] ?? null;

        if ($value) {
            return $value;
        } else {
            $methods = ['_POST', '_GET', '_COOKIE', '_SERVER'];

            foreach ($methods as $method) {
                $pzval = $$method[$name] ?? null;

                if (isset($pzval)) {
                    return $pzval;
                }
            }
        }

        return $default;
    }

    /**
     * @link http://www.php.net/manual/en/yaf-request-simple.isxmlhttprequest.php
     *
     * @return bool
     */
    public function isXmlHttpRequest(): bool
    {
        $header = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? null;

        if ($header && strncasecmp("XMLHttpRequest", $header, strlen($header)) === 0) {
            return true;
        }

        return false;
    }

    // ================================================== 内部方法 ==================================================

    private function instance($module, $controller, $action, $method, $params)
    {
        if (!$method || !is_string($method)) {
            $method = $_SERVER['REQUEST_METHOD'] ?? null;

            if (!$method) {
                if (strtolower(php_sapi_name()) === 'cli') {
                    $method = 'Cli';
                } else {
                    $method = 'Unknow';
                }
            }

        }

        $this->method = $method;

        if ($module || $controller || $action) {
            if ($module) {
                $this->module = $module;
            } else {
                $this->module = YAF_G('default_module');
            }

            if ($controller) {
                $this->controller = $controller;
            } else {
                $this->controller = YAF_G('default_controller');
            }

            if ($action) {
                $this->action = $action;
            } else {
                $this->action = YAF_G('default_action');
            }

            $this->_routed = 1;
        } else {
            $query = $_GET[self::YAF_REQUEST_SERVER_URI] ?? null;
            $this->_uri = $query ?: '';
        }

        $this->_params = $params ?: [];

    }
}
