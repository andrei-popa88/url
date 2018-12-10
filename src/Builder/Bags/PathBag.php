<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;

/**
 * Class PathBag
 *
 * @package Keppler\Builder\Bags
 */
class PathBag
{
    /**
     * @var array
     */
    private $pathComponents = [];

    /**
     * @param array $pathComponents
     *
     * @return PathBag
     */
    public function setPathComponents(array $pathComponents): self
    {
        $this->pathComponents = $pathComponents;

        return $this;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->pathComponents;
    }

    /**
     * @param int $index
     *
     * @return PathBag
     * @throws ComponentNotFoundException
     */
    public function remove(int $index): self
    {
        if ( ! $this->has($index)) {
            throw new ComponentNotFoundException("The component does not exist");
        }

        unset($this->pathComponents[$index]);

        return $this;
    }

    /**
     * @param int $index
     * @param string $newComponent
     *
     * @return PathBag
     * @throws ComponentNotFoundException
     */
    public function overwrite(int $index, string $newComponent): self
    {
        if ( ! $this->has($index)) {
            throw new ComponentNotFoundException("The component does not exist.");
        }

        $this->pathComponents[$index] = $newComponent;

        return $this;
    }

    /**
     * @param string $component
     *
     * @return PathBag
     */
    public function append(string $component): self
    {
        array_push($this->pathComponents, $component);

        return $this;
    }

    /**
     * @param string $component
     *
     * @return PathBag
     */
    public function prepend(string $component): self
    {
        array_unshift($this->pathComponents, $component);

        return $this;
    }

    /**
     * @param int $index
     * @param string $component
     *
     * @return PathBag
     * @throws ComponentNotFoundException
     */
    public function insertAfter(int $index, string $component): self
    {
        if ( ! $this->has($index)) {
            throw new ComponentNotFoundException("The component does not exist");
        }

        $newComponents = [];
        foreach ($this->pathComponents as $key => $value) {
            $newComponents[] = $value;
            if ($index === $key) {
                $newComponents[] = $component;
            }
        }

        $this->pathComponents = $newComponents;

        return $this;
    }

    /**
     * @param int $index
     *
     * @return bool
     */
    public function has(int $index): bool
    {
        return array_key_exists($index, $this->pathComponents);
    }

    /**
     * @param int $index
     * @param string $component
     *
     * @return PathBag
     * @throws ComponentNotFoundException
     */
    public function insertBefore(int $index, string $component): self
    {
        if ( ! $this->has($index)) {
            throw new ComponentNotFoundException("The component does not exist.");
        }

        $newComponents = [];
        foreach ($this->pathComponents as $key => $value) {
            if ($index === $key) {
                $newComponents[] = $component;
            }
            $newComponents[] = $value;
        }

        $this->pathComponents = $newComponents;

        return $this;
    }

    /**
     * @param bool $withTrailingSlash
     *
     * @return string
     */
    public function raw(bool $withTrailingSlash = true): string
    {
        if (empty($this->pathComponents)) {
            return '';
        }

        $path = '/';

        array_map(function ($value) use (&$path) {
            $path .= $value.'/';
        }, $this->pathComponents);

        return true === $withTrailingSlash ? $path : rtrim($path, '/');
    }
}
