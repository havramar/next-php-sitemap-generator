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
    public function createIndex($sitemapsPrefix)
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
            $indexWriter->add($sitemapsPrefix . $this->getSitemapFileName($i+1));
        }
        $indexWriter? $indexWriter->close() : null;
    }

    /**
     * @param int $sitemapNumber Sitemap's number to be included in filename
     *
     * @return string
     */
    private function getSitemapFileName($sitemapNumber)
    {
        return DIRECTORY_SEPARATOR . $this->filenamePrefix . '-'.$sitemapNumber.'.'.$this->extension;
    }

    /**
     * @return string
     */
    protected function getIndexFileName()
    {
        return $this->path . DIRECTORY_SEPARATOR . $this->filenamePrefix . '-index-' . $this->indexCount . '.' . $this->extension;
    }
}
