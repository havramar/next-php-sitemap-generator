<?php

namespace SitemapGenerator\Writer;

interface SitemapWriterInterface
{
    public function add($loc, $priority = '', $lastmod = '', $frequency = '');
}
