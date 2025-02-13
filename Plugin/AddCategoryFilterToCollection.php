<?php

declare(strict_types=1);

namespace Vendic\AdminProductGridCategoryFilter\Plugin;

use Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider;

class AddCategoryFilterToCollection
{
    public function aroundAddFilter(ProductDataProvider $subject, callable $proceed, \Magento\Framework\Api\Filter $filter)
    {
        if ($filter->getField() == 'category_id') {
            return $subject->getCollection()->addCategoriesFilter(array('in' => $filter->getValue()));
        }

        return $proceed($filter);
    }
}
