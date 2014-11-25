<?php

namespace BV\FrontBundle\Services;

class Cache
{
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * Reset cached image
     *
     * @param $path
     * @param $filename
     * @param $uploadDir
     * @param string $filterName
     */
    public function resetCache($path, $filename, $uploadDir, $filterName = 'img_50_50') {
        $webPath = $this->container->get('kernel')->getRootDir() . '/../web';
        $thumbPath = '/media/cache/'.$filterName.$path.'/'.$filename;
        $cachePath = $this->container->get('kernel')->getRootDir() . '/../web'.$thumbPath;
        $imagine = $this->container->get('imagine');
        $imagineFilterManager = $this->container->get('imagine.filter.manager');
        if (file_exists($cachePath))
        {
            $imagineFilterManager->getFilter($filterName)
                ->apply($imagine->open($uploadDir.'/'.$filename))
                ->save($webPath . $thumbPath);
        }
    }

    public function deleteAllFromCache($id)
    {
        $cachePath = $this->container->get('kernel')->getRootDir() . '/../web/media/cache/';
        $directory = new \RecursiveDirectoryIterator($cachePath);
        $iterator = new \RecursiveIteratorIterator($directory);
        $regex = new \RegexIterator($iterator, '/^'.$id.'.+\.*$/i', \RecursiveRegexIterator::GET_MATCH);

        echo $regex; // TODO DELETE FILES
    }
}