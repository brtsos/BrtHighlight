<?php declare(strict_types=1);

namespace BrtHighlight\Components\Listing\Business;

interface SortArticleInterface
{
    /**
     * @param array $sArticles
     * @return array
     */
    public function sort(array $sArticles): array;
}
