<?php
declare(strict_types=1);

namespace Keppler\Url\Parser\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;

/**
 * Class QueryBag
 *
 * @package Url\Bags
 */
class QueryBag
{
    /**
     * @var array
     */
    protected $queryComponents = [];

    /**
     * @var string | null
     */
    protected $queryString = null;

    /**
     * @param string $query
     */
    public function buildQueryComponents(string $query): void
    {
        $this->queryString = $query;
        parse_str($query, $this->queryComponents);
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        return null !== $this->queryString ? $this->queryString : '';
    }

    /**
     * @param $index
     * @return mixed
     * @throws ComponentNotFoundException
     */
    public function firstIn($index)
    {
        if (empty($this->queryComponents)) {
            throw new \LogicException("Cannot get first entry of an empty array");
        }

        $match = $this->inRecursive($this->queryComponents, $index);
        $array_slice = array_slice($match, 0, 1);
        return [key($match) => array_shift($array_slice)];
    }

    /**
     * @param $index
     * @return mixed
     * @throws ComponentNotFoundException
     */
    public function lastIn($index)
    {
        if (empty($this->queryComponents)) {
            throw new \LogicException("Cannot get first entry of an empty array");
        }

        $match = $this->inRecursive($this->queryComponents, $index);

        if(is_array($match)) {
            $array_slice = array_slice($match, -1);
            reset($match);
            end($match);
            return [key($match) => array_shift($array_slice)];
        }

        return $match;
    }
    /**
     * @param $array
     * @param null $match
     * @return mixed
     * @throws ComponentNotFoundException
     */
    private function inRecursive($array, $match = null)
    {
        // Walk the queryComponents and check if the index exists somewhere in the array
        // Match only the first entry since it's basically a hassle to try and match a specific entry
        foreach($array as $key => $value) {
            if(is_array($key)) {
                $this->inRecursive($key);
            }else{
                if($key === $match) {
                    return $value;
                }
            }
        }

        throw new ComponentNotFoundException("Component {$match} does not exist");
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function first(): array
    {
        if (empty($this->queryComponents)) {
            throw new \LogicException("Cannot get first entry of an empty array");
        }

        return [key($this->queryComponents) => $this->queryComponents[key($this->queryComponents)]];
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function last(): array
    {
        if (empty($this->queryComponents)) {
            throw new \LogicException("Cannot get last entry of an empty array");
        }

        $array = $this->queryComponents;
        reset($array);
        end($array);

        return [key($array) => $this->queryComponents[key($array)]];
    }

    /**
     * @param string $component
     *
     * @return bool
     */
    public function has(string $component): bool
    {
        return array_key_exists($component, $this->queryComponents);
    }

    /**
     * @param string $component
     *
     * @return null|string
     */
    public function get(string $component)
    {
        if (empty($this->queryComponents)) {
            throw new \LogicException("Query bag is empty");
        }

        return $this->has($component) ? $this->queryComponents[$component]
            : null;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->queryComponents;
    }

}
