<?php declare(strict_types=1);

namespace BrtHighlight\Components\Listing\Business;

class SortArticle implements SortArticleInterface
{
    /**
     * @param array $sArticles
     * @return array
     */
    public function sort(array $sArticles): array
    {
        usort(
            $sArticles,
            [$this, 'comparison']
        );

        return $sArticles;
    }

    /**
     * @param array $itemOne
     * @param array $itemTwo
     * @return bool
     */
    private function comparison(array $itemOne, array $itemTwo): bool
    {
        return $itemOne['brt_top_on_list'] < $itemTwo['brt_top_on_list'];
    }
}
