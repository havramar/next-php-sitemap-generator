<?php

namespace SitemapGenerator\Creators;

interface IndexCreatorInterface extends CreatorInterface
{
    /**
     *
     * @param string $sitemapsPrefix Prefix path for sitemaps e.g. http://domain.com/sitemaps/
     *
     * @return void
     */
    public function createIndex($sitemapsPrefix);

    /**
     * Set number of sitemaps. Required to know how many links/files should be created.
     *
     * @param int $count Number of sitemaps
     *
     * @return $this
     */
    public function setSitemapsCount($count);

    /**
     * Set custom filename prefix for index.
     *
     * @param string $filePrefix
     * @return $this
     */
    public function setFileNameForIndex($filePrefix);
}
