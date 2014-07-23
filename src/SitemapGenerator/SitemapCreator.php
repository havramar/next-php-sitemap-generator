<?php

namespace SitemapGenerator;

use SitemapGenerator\Creators\AbstractCreator;
use SitemapGenerator\Creators\IndexCreatorInterface;
use SitemapGenerator\Creators\SitemapCreatorInterface;
use SitemapGenerator\Exception\DomainNotSetException;
use SitemapGenerator\Exception\InvalidUrlsCountToLimitRatioException;
use SitemapGenerator\Exception\UrlTooLongException;
use SitemapGenerator\Writer\WriterInterface;

class SitemapCreator extends AbstractCreator implements SitemapCreatorInterface
{
    /**
     * Domain for which URLs are added to sitemap
     *
     * @var string
     */
    protected $domain;

    /**
     * @var integer
     */
    protected $urlsCount = 0;

    /**
     * Number of sitemaps created
     *
     * @var int
     */
    protected $sitemapsCount = 0;

    /**
     * @var WriterInterface
     */
    protected $writer;


    /**
     * Adds url to sitemap
     *
     * @param Url $url Url to be added to sitemap
     *
     * @throws Exception\DomainNotSetException
     * @throws Exception\UrlTooLongException
     */
    public function addUrl(Url $url)
    {
        if (strlen($url->loc) > self::URL_LENGTH_LIMIT) {
            throw new UrlTooLongException();
        }
        $writer = $this->getWriter();

        if (!$this->domain) {
            throw new DomainNotSetException();
        }

        $writer->add($this->domain . $url->loc, $url->priority, $url->lastmod, $url->changefreq);

        $this->urlsCount++;
        if ($this->urlsCount % $this->limit === 0) {
            $this->close();
        }
    }

    /**
     * @param string $domain Domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @param int $limit Limit of urls per sitemap
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Close and flush currently written sitemap file
     *
     * @return void
     */
    public function close()
    {
        if ($this->writer) {
            $this->writer->close();
        }

        $this->writer = null;
    }

    /**
     * Close current file and get sitemap creator for the next file.
     * Useful for forcing next file without reaching limit of the previous one.
     *
     * @return SitemapCreator
     */
    public function getNextSitemapCreator()
    {
        $newCreator = new SitemapCreator();
        $newCreator->setSitemapsCount($this->getSitemapsCount());
        $newCreator->setDomain($this->domain);
        $newCreator->setPath($this->path);
        $newCreator->format = $this->format;
        $this->close();
        return $newCreator;
    }

    /**
     * @return WriterInterface
     */
    protected function getWriter()
    {
        if (!$this->writer) {
            $this->sitemapsCount++;
            $this->urlsCount = 0;

            $factory = $this->getWriterFactory();
            $fullPath = $this->path . $this->getSitemapFileName($this->sitemapsCount);
            $this->writer = $factory->getSitemapWriter($this->format, $fullPath);
        }
        return $this->writer;
    }

    /**
     * @param int $sitemapNumber Sitemap's number to be included in filename
     *
     * @return string
     */
    protected function getSitemapFileName($sitemapNumber)
    {
        return DIRECTORY_SEPARATOR . $this->filenamePrefix . '-'.$sitemapNumber.'.' . $this->extension;
    }

    public function setSitemapsCount($count)
    {
        $this->sitemapsCount = $count;
        return $this;
    }

    /**
     * Get number of sitemaps created.
     *
     * @return int
     */
    public function getSitemapsCount()
    {
        return $this->sitemapsCount;
    }

    /**
     * Build index creator based on current sitemap creator state.
     *
     * @return IndexCreatorInterface
     */
    public function buildIndexCreator()
    {
        $indexCreator = $this->createIndexCreator();
        $indexCreator->setPath($this->path);
        $indexCreator->setFileName($this->filenamePrefix);
        $indexCreator->setLimit($this->limit);
        $indexCreator->setSitemapsCount($this->sitemapsCount);
        $indexCreator->useFormatCustom($this->format, $this->extension);

        return $indexCreator;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @return IndexCreatorInterface
     */
    protected function createIndexCreator()
    {
        return new IndexCreator();
    }
}
