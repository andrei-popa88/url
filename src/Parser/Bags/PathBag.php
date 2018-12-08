<?php
declare(strict_types=1);

namespace Keppler\Url\Parser\Bags;

/**
 * Class PathBag
 *
 * @package Url\Bags
 */
class PathBag
{
    /**
     * @var array
     */
    private $pathComponents = [];

    /**
     * @var null
     */
    private $pathString = null;

    /**
     * @param string $path
     */
    public function buildPathComponents(string $path): void
    {
        $this->pathComponents = explode('/', trim($path, '/'));
        $this->pathString = $path;
    }

    /**
     * @return string
     */
    public function original(): ?string
    {
        return $this->pathString;
    }

    /**
     * @return mixed
     */
    public function last(): ?string
    {
        $arrayKeys = array_keys($this->pathComponents);

        if(empty($arrayKeys)) {
            return null;
        }

        return $this->pathComponents[max($arrayKeys)];
    }

    /**
     * @return null|string
     */
    public function first(): ?string
    {
        return $this->has(0) ? $this->get(0) : null;
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
     *
     * @return string
     */
    public function get(int $index): ?string
    {
        if ($index < 0) {
            $index = abs($index);
        }

        return $this->has($index) ? $this->pathComponents[$index] : null;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->pathComponents;
    }
}
