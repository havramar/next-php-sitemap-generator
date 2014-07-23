<?php

class SitemapCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAddingUrlOutputsValidFile()
    {
        $url = new \SitemapGenerator\Url();
        $url->loc = '/link1';
        $creator = new \SitemapGenerator\SitemapCreator();

        $tmpPath = \tests\Helper::createTempDir();
        $creator->setPath($tmpPath);
        $creator->setFileName('customname');
        $creator->setDomain("http://xxx.com");

        $creator->addUrl($url);

        $creator->close();

        $this->assertFileExists($tmpPath . DIRECTORY_SEPARATOR . 'customname-1.xml');
        $contents = file_get_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-1.xml');
        $expectedContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <url>
  <loc>http://xxx.com/link1</loc>
 </url>
</urlset>

XML;

        $this->assertEquals($expectedContent, $contents);
    }

    public function testSwitchingToNextFileOnLimit()
    {
        $url = new \SitemapGenerator\Url();
        $basicUrl = '/link';
        $creator = new \SitemapGenerator\SitemapCreator();

        $tmpPath = \tests\Helper::createTempDir();
        $creator->setPath($tmpPath);
        $creator->setFileName('customname');
        $creator->setDomain("http://xxx.com");
        $creator->setLimit(2);

        $url->loc = "${basicUrl}1";
        $creator->addUrl($url);
        $url->loc = "${basicUrl}2";
        $creator->addUrl($url);
        $url->loc = "${basicUrl}3";
        $creator->addUrl($url);

        $creator->close();

        $contents = file_get_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-1.xml');
        $expectedContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <url>
  <loc>http://xxx.com/link1</loc>
 </url>
 <url>
  <loc>http://xxx.com/link2</loc>
 </url>
</urlset>

XML;
        $this->assertEquals($expectedContent, $contents);

        $contents = file_get_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-2.xml');
        $expectedContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <url>
  <loc>http://xxx.com/link3</loc>
 </url>
</urlset>

XML;
        $this->assertEquals($expectedContent, $contents);
    }

    public function testAddingUrlsWithOptionalParams()
    {
        $url = new \SitemapGenerator\Url();
        $url->loc = '/link1';
        $url->lastmod = '2005-01-01';
        $url->priority = '0.8';
        $url->changefreq = \SitemapGenerator\Url::FREQ_MONTHLY;
        $creator = new \SitemapGenerator\SitemapCreator();

        $tmpPath = \tests\Helper::createTempDir();
        $creator->setPath($tmpPath);
        $creator->setFileName('customname');
        $creator->setDomain("http://xxx.com");

        $creator->addUrl($url);
        $creator->close();

        $contents = file_get_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-1.xml');
        $expectedContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <url>
  <loc>http://xxx.com/link1</loc>
  <priority>0.8</priority>
  <lastmod>2005-01-01</lastmod>
  <changefreq>monthly</changefreq>
 </url>
</urlset>

XML;

        $this->assertEquals($expectedContent, $contents);
    }

    /**
     * @expectedException \SitemapGenerator\Exception\UrlTooLongException
     */
    public function testExcpetionOnLongUrl()
    {
        $creator = new \SitemapGenerator\SitemapCreator();
        $url = new \SitemapGenerator\Url();
        $url->loc = '/' . str_repeat('x', 2048);
        $creator->addUrl($url);
    }

    public function testCreatingIndexWhenSitemapsAreGenerated()
    {

        $creator = new \SitemapGenerator\SitemapCreator();

        $tmpPath = \tests\Helper::createTempDir();
        $creator->setPath($tmpPath);
        $creator->setFileName('customname');
        $creator->setDomain("http://xxx.com");
        $creator->setLimit(2);

        $url = new \SitemapGenerator\Url();
        $url->loc = '/link1';
        $creator->addUrl($url);
        $url->loc = $url->loc . '1';
        $creator->addUrl($url);
        $url->loc = $url->loc . '2';
        $creator->addUrl($url);
        $creator->close();

        $indexCreator = $creator->buildIndexCreator();
        $indexCreator->setLimit(1);
        $indexCreator->createIndex("http://xxx.com");

        $this->assertFileExists($tmpPath . DIRECTORY_SEPARATOR . 'customname-index-1.xml');
        $contents = file_get_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-index-1.xml');
        $expectedContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <sitemap>
  <loc>http://xxx.com/customname-1.xml</loc>
 </sitemap>
</sitemapindex>

XML;

        $this->assertEquals($expectedContent, $contents);

        $contents = file_get_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-index-2.xml');
        $expectedContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <sitemap>
  <loc>http://xxx.com/customname-2.xml</loc>
 </sitemap>
</sitemapindex>

XML;

        $this->assertEquals($expectedContent, $contents);
    }

    public function testStartingFromCustomSitemapNumber()
    {
        $tmpPath = \tests\Helper::createTempDir();

        $firstFileContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <url>
  <loc>http://xxx.com/link1</loc>
 </url>
</urlset>

XML;

        $secondFileContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <url>
  <loc>http://xxx.com/link2</loc>
 </url>
</urlset>

XML;
        $thirdFileContent = <<<XML
<xml></xml>
<!--shall be gone-->
XML;

        file_put_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-1.xml', $firstFileContent);
        file_put_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-2.xml', $secondFileContent);
        file_put_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-3.xml', $thirdFileContent);

        $creator = new \SitemapGenerator\SitemapCreator();

        $creator->setPath($tmpPath);
        $creator->setFileName('customname');
        $creator->setDomain("http://xxx.com");
        $creator->setLimit(1);
        $creator->setSitemapsCount(2);

        $url = new \SitemapGenerator\Url();
        $url->loc = '/link3';

        $creator->addUrl($url);

        $creator->close();

        $thirdFileContentExpected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <url>
  <loc>http://xxx.com/link3</loc>
 </url>
</urlset>

XML;

        $this->assertFileExists($tmpPath . DIRECTORY_SEPARATOR . 'customname-3.xml');
        $contents = file_get_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-3.xml');

        $this->assertEquals($thirdFileContentExpected, $contents);
    }

    public function testStartingFromCustomTheMiddleOfLastSitemapCreation()
    {
        $tmpPath = \tests\Helper::createTempDir();

        $firstFileContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <url>
  <loc>http://xxx.com/link1</loc>
 </url>
 <url>
  <loc>http://xxx.com/link2</loc>
 </url>
 <url>
  <loc>http://xxx.com/link3</loc>
 </url>
</urlset>

XML;

        $secondFileContentExpected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 <url>
  <loc>http://xxx.com/link4</loc>
 </url>
 <url>
  <loc>http://xxx.com/link5</loc>
 </url>
</urlset>

XML;

        file_put_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-1.xml', $firstFileContent);

        $creator = new \SitemapGenerator\SitemapCreator();

        $creator->setPath($tmpPath);
        $creator->setFileName('customname');
        $creator->setDomain("http://xxx.com");
        $creator->setLimit(5);
        $creator->setSitemapsCount(1);


        $url = new \SitemapGenerator\Url();
        $url->loc = '/link4';
        $creator->addUrl($url);

        $url = new \SitemapGenerator\Url();
        $url->loc = '/link5';
        $creator->addUrl($url);

        $creator->close();

        $this->assertFileExists($tmpPath . DIRECTORY_SEPARATOR . 'customname-2.xml');
        $contents = file_get_contents($tmpPath . DIRECTORY_SEPARATOR . 'customname-2.xml');

        $this->assertEquals($secondFileContentExpected, $contents);
    }
}
