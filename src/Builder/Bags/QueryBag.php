<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Bags;

use Keppler\Url\Exceptions\ComponentNotFoundException;

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
    private $queryComponents = [];

    /**
     * @param array $queryComponents
     */
    public function setQueryComponents(array $queryComponents): void
    {
        $this->queryComponents = $queryComponents;
    }

    /**
     * @return string
     */
    public function buildQuery(): string
    {
        if(empty($this->queryComponents)) {
            return '';
        }

        $query = '?';

        foreach($this->queryComponents as $key => $value){
            $query .= $key . '=' . $value . '&';
        }

        return rtrim($query, '&');
    }

    /**
     * @param string $index
     *
     * @throws ComponentNotFoundException
     */
    public function remove(string $index) : void
    {
        if(!$this->has($index)){
            throw new ComponentNotFoundException("The component does not exist.");
        }

        unset($this->queryComponents[$index]);
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
     * @throws \Exception
     */
    public function overwrite(array $components): self
    {
        if(count($components) !== count($components, COUNT_RECURSIVE)) {
            throw new \Exception("Unable to accept multidimensional arrays");
        }

        foreach($this->queryComponents as $key => $value) {
            foreach($components as $k => $v) {
                if(!$this->has($k)){
                    throw new ComponentNotFoundException("The component does not exist.");
                }

                if($key === $k) {
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
     */
    public function append(array $components): self
    {
        foreach($components as $key => $component) {
            $this->queryComponents[$key] = $component;
        }

        return $this;
    }

    /**
     * @param array $components
     *
     * @return QueryBag
     */
    public function prepend(array $components): self
    {
        $newComponents = [];

        foreach($components as $key => $component) {
            $newComponents[$key] = $component;
        }

        foreach($this->queryComponents as $key => $component) {
            $newComponents[$key] = $component;
        }

        return $this;
    }

    /**
     * @param string $index
     * @param array $components
     *
     * @return QueryBag
     * @throws ComponentNotFoundException
     */
    public function insertAfter(string $index, array $components): self
    {
        if(!$this->has($index)){
            throw new ComponentNotFoundException("The component does not exist.");
        }

        $newComponents = [];
        foreach($this->queryComponents as $key => $component){
            $newComponents[$key] = $component;
            if($index === $key){
                foreach($components as $k => $v) {
                    $newComponents[$k] = $v;
                }
            }
        }

        $this->queryComponents = $newComponents;

        return $this;
    }


    /**
     * @param string $index
     * @param array $components
     *
     * @return QueryBag
     * @throws ComponentNotFoundException
     */
    public function insertBefore(string $index, array $components): self
    {
        if(!$this->has($index)){
            throw new ComponentNotFoundException("The component does not exist.");
        }

        $newComponents = [];
        foreach($this->queryComponents as $key => $component){
            if($index === $key){
                foreach($components as $k => $v) {
                    $newComponents[$k] = $v;
                }
            }
            $newComponents[$key] = $component;
        }

        $this->queryComponents = $newComponents;

        return $this;
    }

}