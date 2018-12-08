<?php
declare(strict_types=1);

namespace Keppler\Url\Parser\Bags;

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
    private $queryComponents = [];

    /**
     * @var string
     */
    private $queryString = null;

    /**
     * @param string $query
     */
    public function buildQueryComponents(string $query): void
    {
        // If the needle is not found it means that we have only one value
        if (false === strpos($query, '&')) {
            $this->queryComponents = explode('=', $query);
        } else {
            $components = explode('&', $query);

            array_map(function ($value) {
                $components = explode('=', $value);
                $this->queryComponents[$components[0]] = $components[1];
            }, $components);
        }

        $this->queryString = $query;
    }

    /**
     * @return null|string
     */
    public function original(): ?string
    {
        return $this->queryString;
    }

    /**
     * @return int|null|string
     */
    public function first(): ?string
    {
        return $this->queryComponents[key($this->queryComponents)];
    }

    /**
     * @return mixed
     */
    public function last(): ?string
    {
        $array = $this->queryComponents;
        reset($array);
        end($array);
        return $this->queryComponents[key($array)];
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
    public function get(string $component): ?string
    {
        return $this->has($component) ? $this->queryComponents[$component] : null;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->queryComponents;
    }

}