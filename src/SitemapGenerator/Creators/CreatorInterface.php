<?php

namespace SitemapGenerator\Creators;

interface CreatorInterface
{
    const URL_LENGTH_LIMIT = 2048;

    /**
     * Path where to store output files
     *
     * @param string $tmpPath Path
     *
     * @return $this
     */
    public function setPath($tmpPath);

    /**
     * Set file prefix.
     *
     * @param string $filePrefix File name prefix
     *
     * @return $this
     */
    public function setFileName($filePrefix);

    /**
     * Set limit of urls per file.
     *
     * @param int $limit Limit of sitemaps per index file
     *
     * @return $this
     */
    public function setLimit($limit);
}
