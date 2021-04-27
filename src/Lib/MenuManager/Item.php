<?php

namespace App\Lib\MenuManager;

/**
 * Class MenuItem
 */
class Item
{
    /**
     * @var string|null
     */
    protected ?string $id;

    /**
     * @var string
     */
    protected string $title;

    /**
     * @var Menu
     */
    protected Menu $menu;

    /**
     * @var string
     */
    protected string $parent;

    /**
     * @var string
     */
    protected string $link;

    /**
     * @var array
     */
    protected array $route = [];

    /**
     * @var Attributes
     */
    protected Attributes $attributes;

    /**
     * @var bool
     */
    protected bool $public = false;

    /**
     * @var bool
     */
    protected bool $visible = true;

    /**
     * @var bool
     */
    protected bool $selected = false;

    /**
     * Item constructor.
     *
     * @param string $title
     * @param Menu $menu
     */
    public function __construct(string $title, Menu $menu)
    {
        $this->title = $title;
        $this->menu = $menu;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return Item
     */
    public function setId(?string $id): Item
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns Item Title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set Parent Item
     *
     * @param string $parent
     *
     * @return Item
     */
    public function setParent(string $parent): Item
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Check if parent Item set
     *
     * @return bool
     */
    public function hasParent(): bool
    {
        return !empty($this->parent);
    }

    /**
     * Returns parent Item
     *
     * @return string
     */
    public function getParent(): ?string
    {
        return $this->parent;
    }

    /**
     * Set Item Link
     *
     * @param string $link
     *
     * @return Item
     */
    public function setLink(string $link): Item
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Return Item Link
     *
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Check if Link is set
     *
     * @return bool
     */
    public function hasLink(): bool
    {
        return !empty($this->link);
    }

    /**
     * Set Item route
     *
     * @param string $name
     * @param array $params
     *
     * @return Item
     */
    public function setRoute(string $name, array $params = []): Item
    {
        $this->route['name'] = $name;
        $this->route['params'] = $params;

        return $this;
    }

    /**
     * Return Item Route
     *
     * @return array
     */
    public function getRoute(): array
    {
        return $this->route;
    }

    /**
     * Check if route is set
     *
     * @return bool
     */
    public function hasRoute(): bool
    {
        return !empty($this->route['name']);
    }

    /**
     * Add Item attribute
     *
     * @param array $attributes
     *
     * @return Item
     */
    public function setAttributes(array $attributes): Item
    {
        $this->attributes = new Attributes($attributes);

        return $this;
    }

    /**
     * Get all Item attributes
     *
     * @return Attributes
     */
    public function getAttributes(): ?Attributes
    {
        return $this->attributes;
    }

    /**
     * Check if attributes was set
     *
     * @return bool
     */
    public function hasAttributes(): bool
    {
        return isset($this->attributes);
    }

    /**
     * Set accessing this Item in view mod. Default false
     *
     * @param bool $public
     *
     * @return Item
     */
    public function setPublic(bool $public): Item
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Check if this Item is accessibility in view mod
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public && ($this->hasParent() ? $this->menu->find($this->parent)->isPublic() : true);
    }

    /**
     * Set visibility this Item
     *
     * @param bool $visible
     *
     * @return Item
     */
    public function setVisible(bool $visible): Item
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Check if this Item is visibility
     *
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible && ($this->hasParent() ? $this->menu->find($this->parent)->isVisible() : true);
    }

    /**
     * Mark Item as selected
     *
     * @param bool $selected
     *
     * @return Item
     */
    public function setSelected(bool $selected): Item
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Check if this Item is selected
     *
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * Create new Item in Menu
     *
     * @param string $title
     *
     * @return Item
     */
    public function addItem(string $title): Item
    {
        return $this->menu->addItem($title);
    }

    /**
     * Building Menu
     *
     * @param bool $publicOnly
     *
     * @return array
     */
    public function build(bool $publicOnly = false): array
    {
        return $this->menu->build($publicOnly);
    }
}