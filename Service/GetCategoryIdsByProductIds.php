<?php

declare(strict_types=1);

namespace Vendic\AdminProductGridCategoryFilter\Service;

use Magento\Framework\App\ResourceConnection;

class GetCategoryIdsByProductIds
{
    public function __construct(
        private ResourceConnection $resourceConnection,
    ) {
    }

    /**
     * Get category ids for products
     *
     * @param array $productIds
     * @return array
     */
    public function execute(array $productIds): array
    {
        $connection = $this->resourceConnection->getConnection();
        $categoryProductTable = $this->resourceConnection->getTableName('catalog_category_product');
        $select = $connection->select()
            ->from(['catalog_category_product' => $categoryProductTable], ['category_id', 'product_id'])
            ->where('product_id IN (?)', $productIds);

        $result =  $connection->fetchAll($select);

        $categoriesByProduct = [];
        foreach ($result as $row) {
            $productId = $row['product_id'];
            $categoriesByProduct[$productId][] = $row['category_id'];
        }

        return $categoriesByProduct;
    }
}
