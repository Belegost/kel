<?php

namespace App\Lib\MenuManager;

/**
 * Class Menu
 */
class Menu
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var Builder
     */
    protected Builder $builder;

    /**
     * @var array
     */
    protected array $items = [];

    /**
     * @var bool
     */
    protected bool $public = false;

    /**
     * @var bool
     */
    protected bool $visible = true;

    /**
     * @var array
     */
    protected array $routes = [];

    protected static array $writableProperties = [
        'public' => 'bool',
        'visible' => 'bool',
        'routes' => 'array',
    ];

    /**
     * Menu constructor.
     *
     * @param string $name
     * @param Builder $builder
     */
    public function __construct(string $name, Builder $builder)
    {
        $this->name = $name;
        $this->builder = $builder;
    }

    /**
     * Returns Menu name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Create new Menu
     *
     * @param string $title
     *
     * @return Item
     */
    public function addItem(string $title): Item
    {
        $item = new Item($title, $this);
        $item->setPublic($this->isPublic())
            ->setVisible($this->isVisible())
            ->setId(strtolower($this->getName()) . '-item-' . count($this->items));

        return ($this->items[$item->getId()] = $item);
    }

    /**
     * Set Menu options
     *
     * @param array $options
     * - public (bool)  Set accessing this Item in view mod. Default FALSE
     * - visible (bool) Set visibility this Item. Default TRUE
     * - routes (array) Set routes for which the menu will be available
     *
     * @return Menu
     */
    public function setOptions(array $options): Menu
    {
        foreach ($options as $name => $value) {
            if (isset(static::$writableProperties[$name]) && gettype($value) == static::$writableProperties[$name]) {
                $this->{$name} = $value;
            }
        }

        return $this;
    }

    /**
     * Check if this Item is accessibility in view mod
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * Check if this Item is visibility
     *
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * Convert Menu to array format
     *
     * @return array
     */
    /**
     * Build menu
     *
     * @param bool $publicOnly
     *
     * @return array
     */
    public function build(bool $publicOnly = false): array
    {
        $result = [];

        if (!$this->checkRoutes() || !$this->isVisible() || ($publicOnly && !$this->isPublic())) {
            return $result;
        }

        foreach ($this->generateItems($publicOnly) as $id => $item) {
            $parent = $item['parent'];
            unset($item['parent']);

            if (!empty($parent)) {
                $parentId = $this->find($parent)->getId();

                if (!isset($result[$parentId]['items'])) {
                    $result[$parentId]['items'] = [];
                }

                $result[$parentId]['items'][] = $item;
            } else {
                $result[$id] = $item;
            }
        }

        return [$this->getName() => $result];
    }

    /**
     * Find Item by title
     *
     * @param string $title
     *
     * @return Item|mixed|null
     */
    public function find(string $title)
    {
        /**
         * @var Item $item
         */
        foreach ($this->items as $id => $item) {
            if ($item->getTitle() == $title) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Generates menu Items
     *
     * @param bool $publicOnly
     *
     * @return \Generator
     */
    protected function generateItems(bool $publicOnly)
    {
        /**
         * @var Item $item
         */
        foreach ($this->items as $id => $item) {
            if (!$item->isVisible() || ($publicOnly && !$item->isPublic())) {
                continue;
            }

            $link = '#';
            if ($item->hasLink()) {
                $link = $item->getLink();
            } else if ($item->hasRoute()) {
                $route = $item->getRoute();
                $link = $this->builder->generateLink($route['name'], $route['params']);
            }

            yield $id => [
                'id' => $id,
                'title' => $item->getTitle(),
                'link' => $link,
                'attributes' => $item->hasAttributes() ? strval($item->getAttributes()) : '',
                'selected' => $item->isSelected(),
                'parent' => $item->hasParent() ? $item->getParent() : '',
            ];
        }
    }

    /**
     * Check if rote is available
     *
     * @return bool
     */
    protected function checkRoutes(): bool
    {
        return empty($this->routes) || in_array($this->builder->getCurrentRoute(), $this->routes);
    }
}
