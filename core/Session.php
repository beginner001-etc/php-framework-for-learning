<?php

/**
 * Session.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
class Session
{
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    /**
     * constructor
     * automatically start Session
     */
    public function __construct()
    {
        if (!self::$sessionStarted) {
            session_start();

            self::$sessionStarted = true;
        }
    }

    /**
     * set value to Session
     *
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * get value from Session
     *
     * @param string $name
     * @param mixed $default Default value when specified key does not exist
     */
    public function get($name, $default = null)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return $default;
    }

    /**
     * remove value from Session
     *
     * @param string $name
     */
    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * empty Session
     */
    public function clear()
    {
        $_SESSION = array();
    }

    /**
     * regenerate Session ID
     *
     * @param boolean $destroy if true, destroy old Session
     */
    public function regenerate($destroy = true)
    {
        if (!self::$sessionIdRegenerated) {
            session_regenerate_id($destroy);

            self::$sessionIdRegenerated = true;
        }
    }

    /**
     * set Authentication status
     *
     * @param boolean
     */
    public function setAuthenticated($bool)
    {
        $this->set('_authenticated', (bool)$bool);

        $this->regenerate();
    }

    /**
     *Whether it is authenticated
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        return $this->get('_authenticated', false);
    }
}
