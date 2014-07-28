<?php

namespace SitemapGenerator\Creators;

use SitemapGenerator\Writer\WriterFactory;

abstract class AbstractCreator implements CreatorInterface
{
    /**
     * Limit of sitemaps per index file
     *
     * @var int
     */
    protected $limit = 50000;

    /**
     * Directory path where to store sitemap/index file
     *
     * @var string
     */
    protected $path;

    /**
     * File name prefix,
     *
     * @var string
     */
    protected $filenamePrefix = 'sitemap';

    /**
     * @var string
     */
    protected $format = 'xml';

    /**
     * @var string
     */
    protected $extension = 'xml';

    /**
     * @var WriterFactory
     */
    protected $writerFactory;

    /**
     * Path where to store output files
     *
     * @param string $path Path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $filePrefix File name prefix
     *
     * @return $this
     */
    public function setFileName($filePrefix)
    {
        $this->filenamePrefix = $filePrefix;
        return $this;
    }

    /**
     * @param int $limit Limit of sitemaps per file
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param $name
     * @param $extension
     *
     * @return $this
     */
    public function useFormatCustom($name, $extension)
    {
        $this->format = $name;
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return $this
     */
    public function useFormatText()
    {
        $this->format = 'text';
        $this->extension = 'txt';
        return $this;
    }

    /**
     * @return $this
     */
    public function useFormatXml()
    {
        $this->format = 'xml';
        $this->extension = 'xml';
        return $this;
    }

    /**
     * @return WriterFactory
     */
    protected function getWriterFactory()
    {
        if (!$this->writerFactory) {
            $this->writerFactory = new WriterFactory();
        }

        return $this->writerFactory;
    }
}
