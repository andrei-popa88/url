<?php
declare(strict_types=1);

namespace Keppler\Url\Builder\Bags;

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
     */
    public function setPathComponents(array $pathComponents): void
    {
        $this->pathComponents = $pathComponents;
    }

}