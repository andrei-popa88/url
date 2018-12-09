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
    protected $pathComponents = [];

    /**
     * @var string | null
     */
    protected $pathString = null;

    /**
     * @param string $path
     */
    public function buildPathComponents(string $path): void
    {
        $this->pathComponents = explode('/', trim($path, '/'));
        $this->pathString = $path;
    }

    /**
     * @return null|string
     */
    public function original(): ?string
    {
        return $this->pathString;
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function last(): ?string
    {
        if (empty($this->pathComponents)) {
            throw new \LogicException("Cannot get last entry in empty array");
        }

        $arrayKeys = array_keys($this->pathComponents);

        return ! empty($arrayKeys) ? $this->pathComponents[max($arrayKeys)]
            : null;
    }

    /**
     * @return null|string
     * @throws \LogicException
     */
    public function first(): ?string
    {
        if (empty($this->pathComponents)) {
            throw new \LogicException("Cannot get first entry in empty array");
        }

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
     *
     * @throws \LogicException
     */
    public function get(int $index): ?string
    {
        if ($index < 0) {
            $index = abs($index);
        }

        if (empty($this->pathComponents)) {
            throw new \LogicException("Path bag is empty");
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
