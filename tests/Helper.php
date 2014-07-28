<?php

namespace tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class Helper
{
    /**
     * @var vfsStreamDirectory
     */
    static private $root;

    /**
     * Return path to create a temporary directory.
     * Every new call deletes content of previous one.
     *
     * @return string
     */
    static public function createTempDir()
    {
        self::$root = vfsStream::setup('root');

        return vfsStream::url('root') . DIRECTORY_SEPARATOR;
    }
}
