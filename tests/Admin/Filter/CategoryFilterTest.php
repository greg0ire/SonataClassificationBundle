<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\ClassificationBundle\Tests\Admin\Filter;

use PHPUnit\Framework\TestCase;
use Sonata\ClassificationBundle\Admin\Filter\CategoryFilter;
use Sonata\ClassificationBundle\Entity\CategoryManager;
use Sonata\ClassificationBundle\Tests\Fixtures\Category;
use Sonata\ClassificationBundle\Tests\Fixtures\Context;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CategoryFilterTest extends TestCase
{
    /**
     * @var MockObject&CategoryManager
     */
    private $categoryManager;

    protected function setUp(): void
    {
        $this->categoryManager = $this->createStub(CategoryManager::class);
    }

    public function testRenderSettings(): void
    {
        $this->categoryManager->method('getAllRootCategories')->willReturn([
            $category = $this->createCategory(),
        ]);

        $filter = new CategoryFilter($this->categoryManager);
        $filter->initialize('field_name', [
            'field_options' => ['class' => 'FooBar'],
            ]);
        $options = $filter->getRenderSettings()[1];

        $this->assertSame(ChoiceType::class, $options['field_type']);
        $this->assertCount(1, $options['field_options']['choices']);
    }

    public function testRenderSettingsWithContext(): void
    {
        $this->categoryManager->method('getCategories')->willReturn([
            $category = $this->createCategory(),
        ]);

        $filter = new CategoryFilter($this->categoryManager);
        $filter->initialize('field_name', [
            'context' => 'foo',
            'field_options' => [
                'class' => 'FooBar',
            ],
        ]);
        $options = $filter->getRenderSettings()[1];

        $this->assertSame(ChoiceType::class, $options['field_type']);
        $this->assertCount(1, $options['field_options']['choices']);
    }

    private function createCategory(): Category
    {
        $category = new Category();
        $category->setContext(new Context());

        return $category;
    }
}
