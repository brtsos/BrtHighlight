<?php declare(strict_types=1);

namespace BrtHighlight\Tests\Unit\Components\Listing\Communication\Subscriber;

use BrtHighlight\Components\Listing\Business\SortArticle;
use BrtHighlight\Tests\Article;
use BrtHighlight\Tests\Category;
use Enlight_Controller_Request_RequestTestCase;
use PHPUnit\Framework\TestCase;
use sArticles;
use Shopware\Bundle\SearchBundle\Condition\CategoryCondition;
use Shopware\Bundle\SearchBundle\Criteria;
use Zend_Db_Adapter_Pdo_Mysql;

class SortArticleTest extends TestCase
{
    use Article, Category;

    private $categoryId;
    private $articleOneId;
    private $articleTwoId;
    private $articleThreeId;

    /**
     * @var sArticles
     */
    private $sArticles;

    /**
     * @var SortArticle
     */
    private $sortArticle;

    /**
     * @var Criteria
     */
    private $criteria;

    /**
     * @var Zend_Db_Adapter_Pdo_Mysql
     */
    private $database;

    protected function setUp()
    {
        parent::setUp();

        $this->database = Shopware()->Db();
        $this->sArticles = Shopware()->Modules()->Articles();
        $this->sortArticle = Shopware()->Container()->get(
            'brt_highlight.components.listing.business.sort_article'
        );

        $this->categoryId = $this->createCategory('Brt Test Category');

        $this->articleOneId = $this->createArticle(
            'Brt Unit Article',
            'BrtUnitArticle',
            1,
            $this->categoryId
        );

        $this->articleTwoId = $this->createArticle(
            'Brt Unit Article 2',
            'BrtUnitArticle2',
            5,
            $this->categoryId
        );

        $this->articleThreeId = $this->createArticle(
            'Brt Unit Article 3',
            'BrtUnitArticle3',
            10,
            $this->categoryId
        );

        $this->setTopAttribute($this->articleOneId, 0);
        $this->setTopAttribute($this->articleTwoId, 0);
        $this->setTopAttribute($this->articleThreeId, 0);

        $request = new Enlight_Controller_Request_RequestTestCase();
        $request->setParam('o', 3);
        Shopware()->Container()->get('front')->setRequest($request);

        $this->criteria = new Criteria();
        $this->criteria->addCondition(new CategoryCondition([$this->categoryId]));
    }

    public function testIfAllArticleHaveNotTopAttribute()
    {
        try {
            $articles = $this->sArticles->sGetArticlesByCategory(
                $this->categoryId,
                $this->criteria
            )['sArticles'];
        } catch (\Enlight_Exception $e) {
            echo $e->getMessage();
        }

        $articles = $this->sortArticle->sort($articles);

        $this->assertCount(3, $articles);

        $this->assertEquals(
            'BrtUnitArticle',
            array_shift($articles)['ordernumber']
        );

        $this->assertEquals(
            'BrtUnitArticle2',
            array_shift($articles)['ordernumber']
        );

        $this->assertEquals(
            'BrtUnitArticle3',
            array_shift($articles)['ordernumber']
        );
    }

    public function testIfOneArticleHasTopAttribute()
    {
        $this->setTopAttribute($this->articleThreeId, 1);

        try {
            $articles = $this->sArticles->sGetArticlesByCategory(
                $this->categoryId,
                $this->criteria
            )['sArticles'];
        } catch (\Enlight_Exception $e) {
            echo $e->getMessage();
        }

        $articles = $this->sortArticle->sort($articles);

        $this->assertCount(3, $articles);

        $this->assertEquals(
            'BrtUnitArticle3',
            array_shift($articles)['ordernumber']
        );

        $this->assertEquals(
            'BrtUnitArticle',
            array_shift($articles)['ordernumber']
        );

        $this->assertEquals(
            'BrtUnitArticle2',
            array_shift($articles)['ordernumber']
        );
    }

    public function testIfTwoArticlesHaveTopAttribute()
    {
        $this->setTopAttribute($this->articleOneId, 1);
        $this->setTopAttribute($this->articleThreeId, 1);

        try {
            $articles = $this->sArticles->sGetArticlesByCategory(
                $this->categoryId,
                $this->criteria
            )['sArticles'];
        } catch (\Enlight_Exception $e) {
            echo $e->getMessage();
        }

        $articles = $this->sortArticle->sort($articles);

        $this->assertCount(3, $articles);

        $this->assertEquals(
            'BrtUnitArticle',
            array_shift($articles)['ordernumber']
        );

        $this->assertEquals(
            'BrtUnitArticle3',
            array_shift($articles)['ordernumber']
        );

        $this->assertEquals(
            'BrtUnitArticle2',
            array_shift($articles)['ordernumber']
        );
    }

    public function testIfThreeArticlesHaveTopAttribute()
    {
        $this->setTopAttribute($this->articleOneId, 1);
        $this->setTopAttribute($this->articleTwoId, 1);
        $this->setTopAttribute($this->articleThreeId, 1);

        try {
            $articles = $this->sArticles->sGetArticlesByCategory(
                $this->categoryId,
                $this->criteria
            )['sArticles'];
        } catch (\Enlight_Exception $e) {
            echo $e->getMessage();
        }

        $articles = $this->sortArticle->sort($articles);

        $this->assertCount(3, $articles);

        $this->assertEquals(
            'BrtUnitArticle',
            array_shift($articles)['ordernumber']
        );

        $this->assertEquals(
            'BrtUnitArticle2',
            array_shift($articles)['ordernumber']
        );

        $this->assertEquals(
            'BrtUnitArticle3',
            array_shift($articles)['ordernumber']
        );
    }

    /**
     * @param int $articleOneId
     * @param int $value
     */
    private function setTopAttribute(int $articleOneId, int $value)
    {
        try {
            $this->database->update(
                's_articles_attributes',
                ['brt_top_on_list' => $value],
                'articleID =' . $articleOneId
            );
        } catch (\Zend_Db_Adapter_Exception $e) {
            echo $e->getMessage();
        }
    }
}
