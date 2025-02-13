<?php

namespace Vendic\AdminProductGridCategoryFilter\Model\Category;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CategoryList implements \Magento\Framework\Option\ArrayInterface
{
    public function __construct(
        private CollectionFactory $collectionFactory
    ) {
    }

    public function toOptionArray() : array
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect('name');

        $options = [];
        $options[] = ['label' => __('-- Please Select a Category --'), 'value' => ''];

        /** @var Category $category */
        foreach ($collection->getItems() as $category) {
            $options[] = [
                'label' => sprintf('%s - %s', $category->getId(), $category->getname()),
                'value' => $category->getId()
            ];
        }

        return $options;
    }
}
