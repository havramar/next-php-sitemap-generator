<?php

namespace SitemapGenerator\Writer\Xml;

use SitemapGenerator\Writer\IndexWriterInterface;

class XmlIndexWriter extends WriterXmlAbstract implements IndexWriterInterface
{
    protected $started = false;

    public function __construct($path)
    {
        $this->setPath($path);
    }

    public function add($loc, $lastmod = '')
    {
        if (!$this->started) {
            $this->renderBeginning();
            $this->started = true;
        }

        $this->xmlWriter->startElement('sitemap');
        $this->xmlWriter->writeElement('loc', $loc);

        $lastmod ? $this->xmlWriter->writeElement('lastmod', $lastmod) : null;

        $this->xmlWriter->endElement();
    }

    protected function renderBeginning()
    {
        $this->xmlWriter->startDocument('1.0', 'UTF-8');
        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->startElement('sitemapindex');
        $this->xmlWriter->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    }

    protected function renderEnd()
    {
        $this->xmlWriter->endElement();
        $this->xmlWriter->endDocument();
    }

    public function close()
    {
        $this->renderEnd();
        parent::close();
    }
}
