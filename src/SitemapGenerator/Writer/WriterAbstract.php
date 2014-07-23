<?php

namespace SitemapGenerator\Writer;

abstract class WriterAbstract implements WriterInterface
{
    protected $path;

    public function setPath($path)
    {
        if (!is_writable(dirname($path))) {
            throw new \InvalidArgumentException("Given path is not writable: $path");
        }

        $this->path = $path;
    }
}
