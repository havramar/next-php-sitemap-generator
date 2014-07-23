<?php

namespace SitemapGenerator\Writer;

class WriterFactory
{
    /**
     * @param $name
     * @param $path
     *
     * @return SitemapWriterInterface
     */
    public function getSitemapWriter($name, $path)
    {
        $classname = $this->resolveName($name, 'sitemap');
        return new $classname($path);
    }

    /**
     * @param $name
     * @param $path
     *
     * @return IndexWriterInterface
     */
    public function getIndexWriter($name, $path)
    {
        $classname = $this->resolveName($name, 'index');
        return new $classname($path);
    }

    protected function resolveName($name, $type)
    {
        if (class_exists($name)) {
            return $name;
        }

        $fullname = __NAMESPACE__ . '\\' . ucfirst($name) . '\\' . ucfirst($name) . ucfirst($type) . 'Writer';

        if (!class_exists($fullname)) {
            throw new \RuntimeException("Cannot load writer class: $fullname");
        }

        return $fullname;
    }
}
