<?php

namespace SitemapGenerator\Writer;

interface IndexWriterInterface
{
    public function add($loc, $lastmod = '');
}
