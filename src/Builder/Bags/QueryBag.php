<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;
use Keppler\Url\Exceptions\InvalidComponentsException;

/**
 * Class QueryBag
 *
 * @package Keppler\Builder\Bags
 */
class QueryBag
{
    /**
     * @var array
     */
    protected $queryComponents = [];

    /**
     * @param array $queryComponents
     *
     * @return QueryBag
     */
    public function setQueryComponents(array $queryComponents): self
    {
        $this->queryComponents = $queryComponents;

        return $this;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->queryComponents;
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        if (empty($this->queryComponents)) {
            return '';
        }

        return urldecode(http_build_query($this->queryComponents));
    }

    /**
     * @param string $index
     * @param bool $recursive
     * @return QueryBag
     * @throws ComponentNotFoundException
     */
    public function remove(string $index, $recursive = false): self
    {
        if(false === $recursive && $this->has($index)) {
            if ( ! $this->has($index)) {
                throw new ComponentNotFoundException("The component does not exist.");
            }

            unset($this->queryComponents[$index]);

            return $this;
        }

        $this->removeRecursive($this->queryComponents, $index);

        return $this;
    }

    /**
     * @param $array
     * @param $index
     */
    private function removeRecursive(&$array, $index)
    {
        foreach ($array as $key => &$value) {
            // Remove all the values encountered, regardless if they're an array or not
            if($key === $index) {
                unset($array[$key]);
            }
            if(is_array($value)) {
                $this->removeRecursive($value, $index);
            }
        }
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    public function has(string $index): bool
    {
        return isset($this->queryComponents[$index]);
    }

    /**
     * @param array $components
     *
     * @return QueryBag
     * @throws ComponentNotFoundException
     * @throws InvalidComponentsException
     * @throws \Exception
     */
    public function overwrite(array $components): self
    {
        if (count($components) !== count($components, COUNT_RECURSIVE)) {
            throw new \Exception("Unable to accept multidimensional arrays");
        }

        if (empty($components)) {
            throw new InvalidComponentsException("Cannot insert empty components");
        }

        foreach ($this->queryComponents as $key => $value) {
            foreach ($components as $k => $v) {
                if ( ! $this->has($k)) {
                    throw new ComponentNotFoundException("The component does not exist.");
                }

                if ($key === $k) {
                    $this->queryComponents[$key] = $v;
                }
            }
        }

        return $this;
    }

    /**
     * @param array $components
     *
     * @return QueryBag
     * @throws InvalidComponentsException
     */
    public function append(array $components): self
    {
        if (empty($components)) {
            throw new InvalidComponentsException("Cannot insert empty components");
        }

        foreach ($components as $key => $component) {
            $this->queryComponents[$key] = $component;
        }

        return $this;
    }

    /**
     * @param array $components
     *
     * @return QueryBag
     * @throws InvalidComponentsException
     */
    public function prepend(array $components): self
    {
        if (empty($components)) {
            throw new InvalidComponentsException("Cannot insert empty components");
        }

        $newComponents = [];

        foreach ($components as $key => $component) {
            $newComponents[$key] = $component;
        }

        foreach ($this->queryComponents as $key => $component) {
            $newComponents[$key] = $component;
        }

        $this->queryComponents = $newComponents;

        return $this;
    }

    /**
     * @param string $index
     * @param array  $components
     *
     * @return QueryBag
     * @throws ComponentNotFoundException
     * @throws InvalidComponentsException
     */
    public function insertAfter(string $index, array $components): self
    {
        if ( ! $this->has($index)) {
            throw new ComponentNotFoundException("The component does not exist.");
        }

        if (empty($components)) {
            throw new InvalidComponentsException("Cannot insert empty components");
        }

        $newComponents = [];
        foreach ($this->queryComponents as $key => $component) {
            $newComponents[$key] = $component;
            if ($index === $key) {
                foreach ($components as $k => $v) {
                    $newComponents[$k] = $v;
                }
            }
        }

        $this->queryComponents = $newComponents;

        return $this;
    }

    /**
     * @param string $index
     * @param array  $components
     *
     * @return QueryBag
     * @throws ComponentNotFoundException
     * @throws InvalidComponentsException
     */
    public function insertBefore(string $index, array $components): self
    {
        if ( ! $this->has($index)) {
            throw new ComponentNotFoundException("The component does not exist.");
        }

        if (empty($components)) {
            throw new InvalidComponentsException("Cannot insert empty components");
        }

        $newComponents = [];
        foreach ($this->queryComponents as $key => $component) {
            if ($index === $key) {
                foreach ($components as $k => $v) {
                    $newComponents[$k] = $v;
                }
            }
            $newComponents[$key] = $component;
        }

        $this->queryComponents = $newComponents;

        return $this;
    }

}
