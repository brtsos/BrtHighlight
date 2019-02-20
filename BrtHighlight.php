<?php declare(strict_types=1);

namespace BrtHighlight;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;

/**
 * @codeCoverageIgnore
 */
class BrtHighlight extends Plugin
{
    public function install(InstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');

        $backendConfiguration = [
            'label' => 'Show on top on list',
            'translatable' => true,
            'displayInBackend' => true,
        ];

        try {
            $service->update(
                's_articles_attributes',
                'brt_top_on_list',
                'boolean',
                $backendConfiguration,
                null,
                false,
                false
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
