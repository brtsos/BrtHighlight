<?php declare(strict_types=1);

namespace BrtHighlight\Components\Listing\Communication\Subscriber;

use BrtHighlight\Components\Listing\Business\SortArticleInterface;
use Enlight\Event\SubscriberInterface;

/**
 * @codeCoverageIgnore
 */
class Listing implements SubscriberInterface
{
    /**
     * @var SortArticleInterface
     */
    private $sortArticle;

    public function __construct(SortArticleInterface $sortArticle)
    {
        $this->sortArticle = $sortArticle;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sArticles::sGetArticlesByCategory::after' => 'onGetArticles',
        ];
    }

    /**
     * @param \Enlight_Hook_HookArgs $args
     */
    public function onGetArticles(\Enlight_Hook_HookArgs $args)
    {
        $return = $args->getReturn();
        $return['sArticles'] = $this->sortArticle->sort($return['sArticles']);
        $args->setReturn($return);
    }
}
