<?php

declare(strict_types=1);

namespace Vendic\AdminProductGridCategoryFilter\Plugin;

use Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider;
use Magento\Framework\Api\Filter;

class AddCategoryFilterToCollection
{
    public function aroundAddFilter(ProductDataProvider $subject, callable $proceed, Filter $filter)
    {
        if ($filter->getField() == 'category_id') {
            return $subject->getCollection()->addCategoriesFilter(array('in' => $filter->getValue()));
        }

        return $proceed($filter);
    }
}
