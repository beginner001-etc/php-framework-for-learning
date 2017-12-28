<?php

/**
 * ClassLoader.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
class ClassLoader
{
    protected $dirs;

    /**
     * register itself in autoload stack
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * register Directory to be autoloaded
     *
     * @param string $dir
     */
    public function registerDir($dir)
    {
        $this->dirs[] = $dir;
    }

    /**
     * load Class
     *
     * @param string $class
     */
    public function loadClass($class)
    {
        foreach ($this->dirs as $dir) {
            $file = $dir . '/' . $class . '.php';
            if (is_readable($file)) {
                require $file;

                return;
            }
        }
    }
}
