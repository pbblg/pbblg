<?php

namespace App\Command\Migration;

use ArrayIterator;
use GlobIterator;
use FilesystemIterator;
use ReflectionClass;
use App\Migrations\AbstractVersion;

class ListCommandHandler
{
    /**
     * @var string
     */
    private $versionFolder;

    public function __construct(array $config)
    {
        $this->versionFolder = $config['version-folder'];
    }

    public function handle()
    {
        $classes = new ArrayIterator();
        $iterator = new GlobIterator(
            sprintf('%s/Version_*.php', $this->versionFolder),
            FilesystemIterator::KEY_AS_FILENAME
        );

        /** @var \SplFileInfo $item */
        foreach ($iterator as $item) {
            if (!preg_match('/(Version_(\d+))\.php/', $item->getFilename(), $matches)) {
                continue;
            }

            $className = 'App\\Migrations\\' . $matches[1];
            if (!class_exists($className)) {
                throw new \RuntimeException("Bad class version in " . $item->getFilename());
            }

            $reflectionClass = new ReflectionClass($className);
            $props = $reflectionClass->getDefaultProperties();
            if (!$reflectionClass->isSubclassOf(AbstractVersion::class)) {
                throw new \RuntimeException("Version must be instanceof App\Migrations\AbstractVersion");
            }
            $classes->append([
                'version' => $matches[2],
                'class' => $className,
                'description' => $props['description'],
                'applied' => 0,
            ]);
        }
        $classes->uasort(
            function ($a, $b) {
                if ($a['version'] == $b['version']) {
                    return 0;
                }
                return ($a['version'] < $b['version']) ? -1 : 1;
            }
        );
        return $classes->getArrayCopy();
    }
}