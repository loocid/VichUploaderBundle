<?php

namespace Vich\UploaderBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * UploaderHelper.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class UploaderHelper extends Helper
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * @var \Vich\UploaderBundle\Storage\StorageInterface $storage
     */
    protected $storage;

    /**
     * @var string $webDirName
     */
    protected $webDirName;

    /**
     * Constructs a new instance of UploaderHelper.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Vich\UploaderBundle\Storage\StorageInterface $storage
     * @param $webDirName
     */
    public function __construct(ContainerInterface $container, StorageInterface $storage, $webDirName)
    {
        $this->container = $container;
        $this->storage = $storage;
        $this->webDirName = $webDirName;
    }
    
    /**
     * Gets the helper name.
     * 
     * @return string The name
     */
    public function getName()
    {
        return 'vich_uploader';
    }
    
    /**
     * Gets the public path for the file associated with the
     * object.
     * 
     * @param object $obj The object.
     * @param string $field The field.
     * @param boolean $absolute True if URL should be absolute, false relative.
     * @return string The public asset path.
     */
    public function asset($obj, $field, $absolute = false)
    {
        $path = $this->storage->resolvePath($obj, $field);

        $index = strpos($path, $this->webDirName);
        $relPath = substr($path, $index + strlen($this->webDirName));

        if ($absolute) {
            return $this->generateAbsoluteUrl($relPath);
        }

        return $relPath;
    }

    /**
     * Generates an absolute path to the file based on the http host.
     *
     * @param string $relPath The relative path.
     * @return string The absolute URL.
     */
    protected function generateAbsoluteUrl($relPath)
    {
        $request = $this->container->get('request');

        return sprintf('%s://%s%s',
            $request->getScheme(),
            $request->getHttpHost(),
            $relPath
        );
    }
}
