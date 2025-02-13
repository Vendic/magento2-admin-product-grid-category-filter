<?php

/**
 * @copyright   Copyright (c) Vendic B.V https://vendic.nl/
 */
declare(strict_types=1);

namespace Vendic\AdminProductGridCategoryFilter\Test\Integration;

use Magento\Framework\Phrase;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use TddWizard\Fixtures\Catalog\CategoryBuilder;
use Vendic\AdminProductGridCategoryFilter\Model\Category\CategoryList;

class CategoryListTest extends TestCase
{
    public function testGetCategoryListOptions(): void
    {
        CategoryBuilder::topLevelCategory()->withName('Category 1')->build();

        /** @var CategoryList $categoryList */
        $categoryList = Bootstrap::getObjectManager()->get(CategoryList::class);

        $options = $categoryList->toOptionArray();

        $createdCategoryOption = array_filter(
            $options,
            fn($option) => str_ends_with($option['label'] instanceof Phrase ? $option['label']->render() : $option['label'], 'Category 1')
        );

        $this->assertCount(1, $createdCategoryOption);
    }
}
