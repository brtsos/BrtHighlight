<?php declare(strict_types=1);

namespace BrtHighlight\Tests;

use Shopware\Components\Api\Exception\CustomValidationException;
use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Api\Manager;

trait Article
{
    /**
     * @param string $name
     * @param string $articleNumber
     * @param float  $price
     * @param int    $categoryId
     * @return int
     */
    protected function createArticle(
        string $name,
        string $articleNumber,
        float $price,
        int $categoryId
    ): int {
        $articleId = (int) Shopware()->Db()->fetchOne(
            'SELECT id FROM s_articles WHERE name = ?',
            [$name]
        );

        if (!$articleId) {
            $articleResource = Manager::getResource('article');
            $articleData = [
                'name' => $name,
                'active' => true,
                'tax' => 19,
                'supplier' => 'Supplier',
                'categories' => array(
                    ['id' => $categoryId],
                ),
                'mainDetail' => [
                    'number' => $articleNumber,
                    'active' => true,
                    'inStock' => 100,
                    'prices' => [
                        [
                            'customerGroupKey' => 'EK',
                            'price' => $price
                        ]
                    ]
                ]
            ];

            try {
                $articleId = $articleResource->create($articleData)->getId();
            } catch (CustomValidationException $e) {
                echo $e->getMessage();
            } catch (ValidationException $e) {
                echo $e->getMessage();
            }
        }

        return $articleId;
    }
}
