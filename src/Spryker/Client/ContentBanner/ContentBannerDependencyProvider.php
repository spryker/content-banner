<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class ContentBannerDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CONTENT_STORAGE = 'CLIENT_CONTENT_STORAGE';

    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->addClientStorage($container);

        return $container;
    }

    protected function addClientStorage(Container $container): Container
    {
        $container->set(static::CLIENT_CONTENT_STORAGE, function (Container $container) {
            return new ContentBannerToContentStorageClientBridge(
                $container->getLocator()->contentStorage()->client(),
            );
        });

        return $container;
    }
}
