<?php

namespace App\Model\Paginator;

use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Default model for pagination view
 */
class PaginatorViewModel
{
    /**
     * Display of objects
     *
     * @var array
     */
    public array $items;

    /**
     * Total result of items
     *
     * @var int
     */
    public int $total;

    /**
     * Page current
     *
     * @var int
     */
    public int $page;

    /**
     * Limit of items to be displayed
     *
     * @var int
     */
    public int $limit;

    /**
     * PaginatorViewModel constructor.
     *
     * @param PaginationInterface $paginator
     * @param callable|null $callback
     */
    public function __construct(PaginationInterface $paginator, callable $callback = null)
    {
        $this->items = null !== $callback
            ? array_map($callback, (array)$paginator->getItems())
            : (array)$paginator->getItems();
        $this->page = $paginator->getCurrentPageNumber();
        $this->limit = $paginator->getItemNumberPerPage();
        $this->total = $paginator->getTotalItemCount();
    }
}
