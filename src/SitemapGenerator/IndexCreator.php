<?php

namespace SitemapGenerator;

use SitemapGenerator\Creators\AbstractCreator;
use SitemapGenerator\Creators\IndexCreatorInterface;

class IndexCreator extends AbstractCreator implements IndexCreatorInterface
{
    /**
     * Number of sitemaps created
     *
     * @var int
     */
    protected $sitemapsCount = 0;

    /**
     * Number of indexes created
     *
     * @var int
     */
    protected $indexCount = 0;

    /**
     * @var string
     */
    protected $indexCustomFilenamePrefix = '';

    /**
     * @param int $count
     * @return $this
     */
    public function setSitemapsCount($count)
    {
        $this->sitemapsCount = $count;
        return $this;
    }

    /**
     * @param string $sitemapsPrefix Prefix path for sitemaps e.g. http://domain.com/sitemaps/
     *
     * @return void
     */
    public function createIndex($sitemapsLocation)
    {
        $indexWriter = null;
        for ($i = 0; $i < $this->sitemapsCount; $i++)
        {
            if ($i % $this->limit === 0) {
                if ($indexWriter) {
                    $indexWriter->close();
                }
                $this->indexCount++;

                $factory = $this->getWriterFactory();
                $path = $this->getIndexFileName();
                $indexWriter = $factory->getIndexWriter($this->format, $path);
            }
            $indexWriter->add($sitemapsLocation . $this->getSitemapFileName($i+1));
        }
        $indexWriter? $indexWriter->close() : null;
    }

    /**
     * @param string $filePrefix
     * @return $this
     */
    public function setFileNameForIndex($filePrefix)
    {
        $this->indexCustomFilenamePrefix = $filePrefix;
        return $this;
    }

    /**
     * @param int $sitemapNumber Sitemap's number to be included in filename
     *
     * @return string
     */
    private function getSitemapFileName($sitemapNumber)
    {
        return $this->filenamePrefix . '-'.$sitemapNumber.'.'.$this->extension;
    }

    /**
     * @return string
     */
    protected function getIndexFileName()
    {
        $filenamePrefix = $this->indexCustomFilenamePrefix ? : $this->filenamePrefix;
        return $this->path . $filenamePrefix . '-index-' . $this->indexCount . '.' . $this->extension;
    }
}
