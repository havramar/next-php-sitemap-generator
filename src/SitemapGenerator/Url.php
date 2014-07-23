<?php

namespace SitemapGenerator;

/**
 * Data Transfer Object for URL
 *
 * @package SitemapGenerator
 */
class Url
{
    const FREQ_ALWAYS   = 'always';
    const FREQ_HOURLY   = 'hourly';
    const FREQ_DAILY    = 'daily';
    const FREQ_WEEKLY   = 'weekly';
    const FREQ_MONTHLY  = 'monthly';
    const FREQ_YEARLY   = 'yearly';
    const FREQ_NEVER    = 'never';

    /**
     * Relative URL, starting with '/'
     *
     * @var string
     */
    public $loc;

    /**
     * @var string
     */
    public $priority;

    /**
     * @var string
     */
    public $lastmod;

    /**
     * @var string
     */
    public $changefreq;
}
