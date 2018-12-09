<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Bags;

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
}