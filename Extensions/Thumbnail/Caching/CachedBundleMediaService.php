<?php

namespace FroshTemplateExtensions\Extensions\Thumbnail\Caching;

use League\Flysystem\FilesystemInterface;
use Shopware\Bundle\MediaBundle\MediaServiceInterface;
use Zend_Cache_Core;

class CachedBundleMediaService implements MediaServiceInterface
{
    /**
     * @var Zend_Cache_Core
     */
    private $cache;

    /**
     * @var MediaServiceInterface
     */
    private $mediaService;

    public function __construct(MediaServiceInterface $mediaService, Zend_Cache_Core $cache)
    {
        $this->mediaService = $mediaService;
        $this->cache = $cache;
    }

    /**
     * Get media path including configured mediaUrl
     *
     * @param string $path
     *
     * @return string|null
     */
    public function getUrl($path)
    {
        return $this->cachedCall(__FUNCTION__, func_get_args());
    }

    /**
     * Read a file.
     *
     * @param string $path the path to the file
     *
     * @return string|false the file contents or false on failure
     */
    public function read($path)
    {
        return $this->mediaService->read($path);
    }

    /**
     * Retrieves a read-stream for a path.
     *
     * @param string $path the path to the file
     *
     * @return resource|false the path resource or false on failure
     */
    public function readStream($path)
    {
        return $this->mediaService->readStream($path);
    }

    /**
     * Create a file or update if exists using a string as content.
     *
     * @param string $path the path to the file
     * @param string $contents the file contents
     * @param bool $append Append file
     *
     * @return bool true on success, false on failure
     */
    public function write($path, $contents, $append = false)
    {
        $this->mediaService->write($path, $contents, $append);
    }

    /**
     * Write a new file using a stream.
     *
     * @param string $path the path of the new file
     * @param resource $resource the file handle
     * @param bool $append Append file
     *
     * @return bool true on success, false on failure
     */
    public function writeStream($path, $resource, $append = false)
    {
        $this->mediaService->writeStream($path, $resource, $append);
    }

    /**
     * List files of the file system
     *
     * @param string $directory
     *
     * @return array a list of file paths
     */
    public function listFiles($directory = '')
    {
        $this->mediaService->listFiles($directory);
    }

    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public function has($path)
    {
        return $this->cachedCall(__FUNCTION__, func_get_args());
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @return bool true on success, false on failure
     */
    public function delete($path)
    {
        return $this->mediaService->delete($path);
    }

    /**
     * Get a file's size.
     *
     * @param string $path the path to the file
     *
     * @return int|false the file size or false on failure
     */
    public function getSize($path)
    {
        return $this->mediaService->getSize($path);
    }

    /**
     * Rename a file.
     *
     * @param string $path path to the existing file
     * @param string $newpath the new path of the file
     *
     * @return bool true on success, false on failure
     */
    public function rename($path, $newpath)
    {
        return $this->mediaService->rename($path, $newpath);
    }

    /**
     * Normalizes the path based on the configured strategy
     *
     * @param string $path
     *
     * @return string
     */
    public function normalize($path)
    {
        return $this->mediaService->normalize($path);
    }

    /**
     * Builds the path on the filesystem
     *
     * @param string $path
     *
     * @return string
     */
    public function encode($path)
    {
        return $this->mediaService->encode($path);
    }

    /**
     * Checks if the provided path matches the algorithm format
     *
     * @param string $path
     *
     * @return bool
     */
    public function isEncoded($path)
    {
        return $this->mediaService->isEncoded($path);
    }

    /**
     * Returns the adapter type. e.g. 'local'
     *
     * @return string
     */
    public function getAdapterType()
    {
        return $this->mediaService->getAdapterType();
    }

    /**
     * Create a directory.
     *
     * @param string $dirname the name of the new directory
     *
     * @return bool true on success, false on failure
     */
    public function createDir($dirname)
    {
        return $this->mediaService->createDir($dirname);
    }

    /**
     * Migrates a file to the new strategy if it's not present
     *
     * @param string $path
     *
     * @return bool
     */
    public function migrateFile($path)
    {
        return $this->mediaService->migrateFile($path);
    }

    /**
     * @return FilesystemInterface
     */
    public function getFilesystem()
    {
        return $this->mediaService->getFilesystem();
    }

    private function cachedCall($function, array $arguments)
    {
        $id = md5($function . json_encode($arguments));

        if ($this->cache->test($id)) {
            return $this->cache->load($id, true);
        }

        $response = call_user_func_array([$this->mediaService, $function], $arguments);

        $this->cache->save($response, $id, ['Shopware_Config'], 86400);

        return $response;
    }
}