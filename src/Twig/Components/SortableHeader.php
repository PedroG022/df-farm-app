<?php

namespace App\Twig\Components;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class SortableHeader
{
    public string $attribute;
    public string $label;
    public PaginationInterface $pagination;
}
