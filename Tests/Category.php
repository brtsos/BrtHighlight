<?php declare(strict_types=1);

namespace BrtHighlight\Tests;

use Shopware\Components\Api\Exception\ValidationException;
use Shopware\Components\Api\Manager;

trait Category
{
    /**
     * @param string $categoryName
     * @return int
     */
    protected function createCategory(string $categoryName): int
    {
        $catId = (int) Shopware()->Db()->fetchOne(
            'SELECT
                    id
                 FROM
                    s_categories
                 WHERE
                    description = ?',
            [$categoryName]
        );

        if (!$catId) {
            $categoryResource = Manager::getResource('category');
            $categoryData = [
                'name' => $categoryName,
                'active' => true,
                'parent' => 3,
            ];

            try {
                $catId = $categoryResource->create($categoryData)->getId();
            } catch (ValidationException $e) {
                echo $e->getMessage();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        return $catId;
    }
}
