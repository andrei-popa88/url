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
    protected $pathComponents = [];

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
     * @param string $component
     *
     * @return PathBag
     * @throws ComponentNotFoundException
     */
    public function remove(string $component): self
    {
        if ( ! $this->has($component)) {
            throw new ComponentNotFoundException("The component does not exist.");
        }

        foreach ($this->pathComponents as $key => $pathComponent) {
            if ($pathComponent === $component) {
                unset($this->pathComponents[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * @param string $oldComponent
     * @param string $newComponent
     *
     * @return PathBag
     * @throws ComponentNotFoundException
     */
    public function overwrite(string $oldComponent, string $newComponent): self
    {
        if ( ! $this->has($oldComponent)) {
            throw new ComponentNotFoundException("The component does not exist.");
        }

        foreach ($this->pathComponents as $key => $value) {
            if ($oldComponent === $value) {
                $this->pathComponents[$key] = $newComponent;
                break;
            }
        }

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
     * @param string $index
     * @param string $component
     *
     * @return PathBag
     * @throws ComponentNotFoundException
     */
    public function insertAfter(string $index, string $component): self
    {
        if ( ! $this->has($index)) {
            throw new ComponentNotFoundException("The component does not exist.");
        }

        $newComponents = [];
        foreach ($this->pathComponents as $value) {
            $newComponents[] = $value;
            if ($index === $value) {
                $newComponents[] = $component;
            }
        }

        $this->pathComponents = $newComponents;

        return $this;
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    public function has(string $index): bool
    {
        return in_array(trim($index), $this->pathComponents);
    }

    /**
     * @param string $index
     * @param string $component
     *
     * @return PathBag
     * @throws ComponentNotFoundException
     */
    public function insertBefore(string $index, string $component): self
    {
        if ( ! $this->has($index)) {
            throw new ComponentNotFoundException("The component does not exist.");
        }

        $newComponents = [];
        foreach ($this->pathComponents as $value) {
            if ($index === $value) {
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
    public function buildPath(bool $withTrailingSlash = true): string
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
