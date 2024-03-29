<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner\Mapper;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface;
use Spryker\Client\ContentBanner\Exception\MissingBannerTermException;

class ContentBannerTypeMapper implements ContentBannerTypeMapperInterface
{
    /**
     * @var \Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface
     */
    protected $contentStorageClient;

    /**
     * @var array<\Spryker\Client\ContentBanner\Executor\ContentBannerTermExecutorInterface>
     */
    protected $contentBannerTermExecutors;

    /**
     * @param \Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface $contentStorageClient
     * @param array<\Spryker\Client\ContentBanner\Executor\ContentBannerTermExecutorInterface> $contentBannerTermExecutors
     */
    public function __construct(ContentBannerToContentStorageClientInterface $contentStorageClient, array $contentBannerTermExecutors)
    {
        $this->contentStorageClient = $contentStorageClient;
        $this->contentBannerTermExecutors = $contentBannerTermExecutors;
    }

    /**
     * @param string $contentKey
     * @param string $localeName
     *
     * @throws \Spryker\Client\ContentBanner\Exception\MissingBannerTermException
     *
     * @return \Generated\Shared\Transfer\ContentBannerTypeTransfer|null
     */
    public function executeBannerTypeByKey(string $contentKey, string $localeName): ?ContentBannerTypeTransfer
    {
        $contentTypeContextTransfer = $this->contentStorageClient->findContentTypeContextByKey($contentKey, $localeName);

        if (!$contentTypeContextTransfer) {
            return null;
        }

        $term = $contentTypeContextTransfer->getTerm();

        if (!isset($this->contentBannerTermExecutors[$term])) {
            throw new MissingBannerTermException(sprintf('There is no ContentBanner Term which can work with the term %s.', $term));
        }

        $bannerTermToBannerTypeExecutor = $this->contentBannerTermExecutors[$term];

        return $bannerTermToBannerTypeExecutor->execute($contentTypeContextTransfer);
    }

    /**
     * @param array<string> $contentKeys
     * @param string $localeName
     *
     * @return array<string, \Generated\Shared\Transfer\ContentBannerTypeTransfer>
     */
    public function executeBannerTypeByKeys(array $contentKeys, string $localeName): array
    {
        $contentTypeContextTransfers = $this->contentStorageClient->getContentTypeContextByKeys(
            $contentKeys,
            $localeName,
        );

        if (!$contentTypeContextTransfers) {
            return [];
        }

        $contentBannerTypeTransfers = [];
        foreach ($contentTypeContextTransfers as $contentTypeContextTransfer) {
            $term = $contentTypeContextTransfer->getTerm();
            if (!isset($this->contentBannerTermExecutors[$term])) {
                return [];
            }

            $bannerTermToBannerTypeExecutor = $this->contentBannerTermExecutors[$term];

            $contentBannerTypeTransfers[$contentTypeContextTransfer->getKey()] = $bannerTermToBannerTypeExecutor->execute($contentTypeContextTransfer);
        }

        return $contentBannerTypeTransfers;
    }
}
