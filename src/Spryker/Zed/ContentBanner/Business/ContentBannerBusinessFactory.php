<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBanner\Business;

use Spryker\Zed\ContentBanner\Business\Model\ContentBannerConstraintsProvider;
use Spryker\Zed\ContentBanner\Business\Model\ContentBannerConstraintsProviderInterface;
use Spryker\Zed\ContentBanner\Business\Model\ContentBannerValidator;
use Spryker\Zed\ContentBanner\Business\Model\ContentBannerValidatorInterface;
use Spryker\Zed\ContentBanner\ContentBannerDependencyProvider;
use Spryker\Zed\ContentBanner\Dependency\External\ContentBannerToValidationAdapterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ContentBanner\ContentBannerConfig getConfig()
 */
class ContentBannerBusinessFactory extends AbstractBusinessFactory
{
    public function createContentBannerValidator(): ContentBannerValidatorInterface
    {
        return new ContentBannerValidator(
            $this->getValidatorAdapter(),
            $this->createContentBannerConstraintsProvider(),
        );
    }

    public function getValidatorAdapter(): ContentBannerToValidationAdapterInterface
    {
        return $this->getProvidedDependency(ContentBannerDependencyProvider::ADAPTER_VALIDATION);
    }

    public function createContentBannerConstraintsProvider(): ContentBannerConstraintsProviderInterface
    {
        return new ContentBannerConstraintsProvider();
    }
}
