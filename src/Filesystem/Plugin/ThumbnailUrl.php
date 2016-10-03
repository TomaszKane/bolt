<?php

namespace Bolt\Filesystem\Plugin;

use Bolt\Filesystem\FilesystemInterface;
use Bolt\Filesystem\PluginInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Generates thumbnail urls.
 *
 * @author Carson Full <carsonfull@gmail.com>
 */
class ThumbnailUrl implements PluginInterface
{
    /** @var UrlGeneratorInterface */
    protected $urlGenerator;
    /** @var FilesystemInterface */
    protected $filesystem;

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return 'thumb';
    }

    /**
     * {@inheritdoc}
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Generate thumbnail url.
     *
     * @param string $path
     * @param int    $width
     * @param int    $height
     * @param string $action
     *
     * @return string
     */
    public function handle($path, $width, $height, $action)
    {
        $file = $this->filesystem->getFile($path);

        return $this->urlGenerator->generate(
            'thumb',
            [
                'width'  => $width,
                'height' => $height,
                'action' => $action[0], // first char
                'file'   => $file->getPath(),
            ]
        );
    }
}
