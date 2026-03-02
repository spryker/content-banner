<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBanner;

use Spryker\Zed\ContentBanner\Dependency\External\ContentBannerToValidationAdapter;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentBanner\ContentBannerConfig getConfig()
 */
class ContentBannerDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const ADAPTER_VALIDATION = 'ADAPTER_VALIDATION';

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addValidationAdapter($container);

        return $container;
    }

    protected function addValidationAdapter(Container $container): Container
    {
        $container->set(static::ADAPTER_VALIDATION, function () {
            return new ContentBannerToValidationAdapter();
        });

        return $container;
    }
}
