<?php

namespace SitemapGenerator\Writer\Xml;

use SitemapGenerator\Writer\SitemapWriterInterface;

class XmlSitemapWriter extends WriterXmlAbstract implements SitemapWriterInterface
{
    protected $started = false;

    public function __construct($path)
    {
        $this->setPath($path);
    }

    public function add($loc, $priority = '', $lastmod = '', $frequency = '')
    {
        if (!$this->started) {
            $this->renderBeginning();
            $this->started = true;
        }

        $this->xmlWriter->startElement('url');
        $this->xmlWriter->writeElement('loc', $loc);

        $priority ? $this->xmlWriter->writeElement('priority', $priority) : '';
        $lastmod ? $this->xmlWriter->writeElement('lastmod', $lastmod) : '';
        $frequency ? $this->xmlWriter->writeElement('changefreq', $frequency) : '';

        $this->xmlWriter->endElement();
    }

    protected function renderBeginning()
    {
        $this->xmlWriter->startDocument('1.0', 'UTF-8');
        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->startElement('urlset');
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
