<?php

namespace App\Tests\Feature\Http\Controllers\Products;

use App\DataFixtures\MoreThan5ProductsFixtures;
use App\DataFixtures\ProductsFixtures;
use App\DataFixtures\PromotionsFixtures;
use App\DataFixtures\PromotionSkuFixture;
use App\Tests\Shared\Infrastructure\PhpUnit\FeatureTestCase;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class ListProductsControllerTest extends FeatureTestCase
{
    use RecreateDatabaseTrait;

    /** Product List To compare on this feature test */
    use ProductListTrait;

    public const ENDPOINT = '/products';
    private static KernelBrowser $baseClient;
    private AbstractDatabaseTool $databaseTool;

    protected function setUp(): void
    {
        self::$baseClient = static::createClient(['environment' => 'test']);
        $this->databaseTool = $this->service(DatabaseToolCollection::class)->get();
        self::$baseClient->setServerParameters([
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ]);
    }

    public function testShouldReturnProductsWithDiscount()
    {
        $this->databaseTool->loadFixtures([
            PromotionsFixtures::class,
            ProductsFixtures::class,
        ]);
        self::$baseClient->request('GET', self::ENDPOINT);

        $response = self::$baseClient->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('products', $content);
        $this->assertIsArray($content['products']);
        $this->assertCount(5, $content['products']);

        $this->assertIsSimilar($this->productsAllDiscount, $content['products']);
    }

    public function testShouldReturnWithoutDiscount()
    {
        $this->databaseTool->loadFixtures([
            ProductsFixtures::class,
        ]);
        self::$baseClient->request('GET', self::ENDPOINT);

        $response = self::$baseClient->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('products', $content);
        $this->assertIsArray($content['products']);
        $this->assertCount(5, $content['products']);

        $this->assertIsSimilar($this->productsNotDiscount, $content['products']);
    }

    public function testShouldReturnProductsWithDiscountSku()
    {
        $this->databaseTool->loadFixtures([
            PromotionSkuFixture::class,
            ProductsFixtures::class,
        ]);
        self::$baseClient->request('GET', self::ENDPOINT);

        $response = self::$baseClient->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('products', $content);
        $this->assertIsArray($content['products']);
        $this->assertCount(5, $content['products']);

        $this->assertIsSimilar($this->productsSkuDiscount, $content['products']);
    }

    public function testShouldReturnOnly5Products()
    {
        $this->databaseTool->loadFixtures([
            MoreThan5ProductsFixtures::class,
        ]);
        self::$baseClient->request('GET', self::ENDPOINT);

        $response = self::$baseClient->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('products', $content);
        $this->assertIsArray($content['products']);
        $this->assertCount(5, $content['products']);
        $this->assertEquals(9, $content['total']);
        $this->assertEquals(1, $content['page']);

        self::$baseClient->request('GET', self::ENDPOINT, ['page' => 2]);

        $response = self::$baseClient->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertCount(4, $content['products']);
        $this->assertEquals(9, $content['total']);
        $this->assertEquals(2, $content['page']);
    }

    public function testShouldReturnProductsLessThan90000Price()
    {
        $this->databaseTool->loadFixtures([
            ProductsFixtures::class,
        ]);
        self::$baseClient->request('GET', self::ENDPOINT, ['priceLessThan' => 90000]);

        $response = self::$baseClient->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('products', $content);
        $this->assertIsArray($content['products']);
        $this->assertCount(4, $content['products']);

        $this->assertIsSimilar($this->productsLessThan9000, $content['products']);
    }

    public function testShouldReturnOnlyBootsCategory()
    {
        $this->databaseTool->loadFixtures([
            ProductsFixtures::class,
        ]);
        self::$baseClient->request('GET', self::ENDPOINT, ['category' => 'boots']);

        $response = self::$baseClient->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('products', $content);
        $this->assertIsArray($content['products']);
        $this->assertCount(3, $content['products']);

        $this->assertIsSimilar($this->productOnlyBootsCategory, $content['products']);
    }
}
