<?php

namespace App\Model;

/**
 * Driver Model to report the filters
 */
class DriverReportFiltersModel extends FiltersModel
{
    /**
     * @var string|null
     */
    public ?string $name = null;

    /**
     * @var string|null
     */
    public ?string $document = null;

    /**
     * @var string|null
     */
    public ?string $plate = null;

}
