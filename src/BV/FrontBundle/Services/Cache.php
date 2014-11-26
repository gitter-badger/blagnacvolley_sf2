<?php

namespace BV\FrontBundle\Services;

use Symfony\Component\Finder\Finder;

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

    /**
     * Delete all files from cache sub directories of type $type for which name match $id.*
     *
     * @param $id
     * @param $type
     */
    public function deleteFilesFromCache($id, $type)
    {
        $cachePath = $this->container->get('kernel')->getRootDir() . '/../web/media/cache/';
        $finder = new Finder();
        $finder->directories()->in($cachePath);
        foreach ($finder as $filter) {
            $path = $cachePath.$filter->getRelativePathname().'/uploads/'.$type;
            if (is_dir($path)) {
                $finder2 = new Finder();
                $finder2->files()->in($path)->name('/^'.$id.'.[\w]+$/i');
                foreach ($finder2 as $file)
                {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Delete a Single File from Upload Directory
     *
     * @param $id
     * @param $type
     */
    public function deleteFileFromUploadDir($id, $type)
    {
        $uploadPath = $this->container->get('kernel')->getRootDir() . '/../web/uploads/'.$type;
        if (is_dir($uploadPath)) {
            $finder = new Finder();
            $finder->files()->in($uploadPath)->name('/^'.$id.'.[\w]+$/i');
            foreach ($finder as $file) {
                unlink($file);
            }
        }
    }
}