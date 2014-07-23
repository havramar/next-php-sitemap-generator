<?php

namespace tests\SitemapGenerator\Writer\Text;

use SitemapGenerator\Writer\Text\TextSitemapWriter;
use tests\Helper;

class TextSitemapWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testTextSitemapWriterWritingUrls()
    {
        $path = Helper::createTempDir() . '/name.txt';

        $writer = new TextSitemapWriter($path);

        $writer->add('http://someurl');
        $writer->close();

        $this->assertFileExists($path);
        $this->assertEquals('http://someurl'.PHP_EOL, file_get_contents($path));
    }

}
