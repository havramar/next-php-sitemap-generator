<?php

namespace SitemapGenerator\Creators;

use SitemapGenerator\Exception\UrlTooLongException;
use SitemapGenerator\Url;

interface SitemapCreatorInterface extends CreatorInterface
{
    /**
     * Adds url to sitemap
     *
     * @param Url $url Url to be added to sitemap
     *
     * @throws UrlTooLongException
     */
    public function addUrl(Url $url);

    /**
     * @param string $domain Domain
     * @return $this
     */
    public function setDomain($domain);

    /**
     * Get number of sitemaps created.
     *
     * @return int
     */
    public function getSitemapsCount();

    /**
     * Build index creator based on current sitemap creator state.
     *
     * @return IndexCreatorInterface
     */
    public function buildIndexCreator();
}
