# next-php-sitemap-generator

Yet another sitemap generator in PHP.

[![Build Status](https://travis-ci.org/havramar/next-php-sitemap-generator.svg?branch=master)](https://travis-ci.org/havramar/next-php-sitemap-generator)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5fb9272b-9b24-477b-bd3a-8ac9c4eb8844/mini.png)](https://insight.sensiolabs.com/projects/5fb9272b-9b24-477b-bd3a-8ac9c4eb8844)

## How to run


1. Get [composer](https://getcomposer.org/)

  ```bash
  curl -sS https://getcomposer.org/installer | php
  ```

2. Install dependencies

  ```bash
  php composer.phar install
  ```

3. Create example script `example.php`

  ```php
  <?php
  
  require_once 'vendor/autoload.php';
  
  $sitemapCreator = new \SitemapGenerator\SitemapCreator();
  $sitemapCreator->setDomain('http://www.example.com');
  
  $url = new \SitemapGenerator\Url();
  $url->loc = '/home';
  $url->changefreq = \SitemapGenerator\Url::FREQ_HOURLY;
  
  $sitemapCreator->addUrl($url);
  
  $indexCreator = $sitemapCreator->buildIndexCreator();
  $indexCreator->createIndex('http://www.example.com/sitemaps/');
  ```

4. Run example

  ```bash
  php example.php
  ```

5. Two files were created in current directory

  * sitemap-1.xml
  
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
     <url>
      <loc>http://www.example.com/home</loc>
      <changefreq>hourly</changefreq>
     </url>
    </urlset>
    ```
  
  * sitemap-index-1.xml
  
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
     <sitemap>
      <loc>http://www.example.com/sitemaps/sitemap-1.xml</loc>
     </sitemap>
    </sitemapindex>
    ```
