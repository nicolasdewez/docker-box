<?php

namespace App\Service;

use App\Entity\Container;
use App\Exception\InvalidArgumentException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * Class Configuration.
 */
class Configuration
{
    /** @var Serializer */
    protected $serializer;

    /** @var string */
    protected $pathFile;

    /**
     * Initialize serializer and path files.
     */
    public function __construct()
    {
        $this->serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);
        $this->pathFile = __PATH_CONFIGURATION__;
    }

    /**
     * @param  string
     *
     * @return Container
     *
     * @throws InvalidArgumentException
     */
    public function load($name)
    {
        $path = sprintf('%s/%s.json', $this->pathFile, $name);
        if (!is_file($path)) {
            throw new InvalidArgumentException(sprintf('Container %s not found', $name));
        }

        $json = file_get_contents($path);

        return $this->serializer->deserialize($json, 'App\Entity\Container', 'json');
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function exists($name)
    {
        $path = sprintf('%s/%s.json', $this->pathFile, $name);

        return is_file($path);
    }

    /**
     * @param bool $index
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function lists($index = true)
    {
        $finder = new Finder();
        $configurations = $finder->files()->in($this->pathFile)->sortByName();

        $containers = [];
        foreach ($configurations as $configuration) {
            $container = $this->load($configuration->getBasename('.json'));
            if ($index) {
                $containers[$container->getName()] = $container;
            } else {
                $containers[] = $container;
            }
        }

        return $containers;
    }

    /**
     * @param Container $container
     */
    public function save(Container $container)
    {
        $path = sprintf('%s/%s.json', $this->pathFile, $container->getName());

        $json = $this->serializer->serialize($container, 'json');
        file_put_contents($path, $json);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function delete($name)
    {
        if (!$this->exists($name)) {
            return false;
        }

        $path = sprintf('%s/%s.json', $this->pathFile, $name);

        return unlink($path);
    }
}
