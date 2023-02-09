<?php

namespace App\Model\Paginator;

use App\Form\PaginatorType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Default model for pagination validation
 */
class PaginatorModel
{
    const FORM_TYPE = PaginatorType::class;

    /**
     * @var int
     */
    #[Assert\Range(min: 1, max: 500)]
    public int $limit;

    /**
     * @var int
     */
    #[Assert\Range(min: 1)]
    public int $page;
}
