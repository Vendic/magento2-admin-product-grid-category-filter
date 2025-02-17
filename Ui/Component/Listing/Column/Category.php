<?php

namespace Vendic\AdminProductGridCategoryFilter\Ui\Component\Listing\Column;

use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Pricing\Price\Collection;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Vendic\AdminProductGridCategoryFilter\Service\GetCategoryIdsByProductIds;

class Category extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function __construct(
        private GetCategoryIdsByProductIds $getCategoryIdsByProductIds,
        private CollectionFactory $collectionFactory,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        $productIds = array_column($dataSource['data']['items'], 'entity_id');
        if (empty($productIds)) {
            return $dataSource;
        }

        $categoryIdsPerProduct = $this->getCategoryIdsByProductIds->execute($productIds);

        /** @var CategoryCollection $categoryCollection */
        $categoryCollection = $this->collectionFactory->create();
        $categoryCollection->addAttributeToSelect('name');
        $categoryCollection->addIdFilter(array_unique(array_merge(...$categoryIdsPerProduct)));

        if ($categoryCollection->count() === 0) {
            return $dataSource;
        }

        $items = $categoryCollection->getItems();

        $fieldName = $this->getData('name');
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $productId = $item['entity_id'];
                $categoryIds = $categoryIdsPerProduct[$productId] ?? [];
                if (count($categoryIds) === 0) {
                    continue;
                }

                $categoryNames = [];
                foreach ($categoryIds as $categoryId) {
                    $category = $items[$categoryId] ?? null;
                    if ($category) {
                        $categoryNames[] = $category->getName();
                    }
                }

                $item[$fieldName] = implode(', ', $categoryNames);
            }
        }
        return $dataSource;
    }
}
