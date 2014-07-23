<?php

namespace SitemapGenerator\Writer\Xml;

use SitemapGenerator\Writer\WriterAbstract;

abstract class WriterXmlAbstract extends WriterAbstract
{
    /**
     * @var \XMLWriter
     */
    protected $xmlWriter;

    /**
     * @param string $path Path to be set
     *
     * @return $this
     */
    public function setPath($path)
    {
        parent::setPath($path);
        $this->xmlWriter = new \XMLWriter();
        $this->xmlWriter->openUri($path);

        return $this;
    }

    /**
     * Close current writer and flush if needed.
     *
     * @return void
     */
    public function close()
    {
        if ($this->xmlWriter) {
            $this->xmlWriter->flush();
        }
        $this->xmlWriter = null;
    }
}
