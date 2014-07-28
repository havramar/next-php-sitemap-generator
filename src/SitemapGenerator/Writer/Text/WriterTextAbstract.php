<?php

namespace SitemapGenerator\Writer\Text;

use SitemapGenerator\Writer\WriterAbstract;

abstract class WriterTextAbstract extends WriterAbstract
{
    /**
     * @var resource
     */
    protected $fh;

    public function __construct($path)
    {
        $this->setPath($path);
        $this->fh = fopen($path, 'w');
    }

    public function add($loc, $lastmod = '')
    {
        fwrite($this->fh, $loc . PHP_EOL);
    }

    public function close()
    {
        fclose($this->fh);
    }
}
