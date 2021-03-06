<?php
/**
 * Test class for \Magento\Store\Model\Store\Service\StoreConfigManager
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Store\Test\Unit\Model\Service;

use Magento\Store\Model\ScopeInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class StoreConfigManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Store\Model\Service\StoreConfigManager
     */
    protected $model;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Magento\Store\Model\ResourceModel\Store\CollectionFactory
     */
    protected $storeCollectionFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Magento\Store\Model\Data\StoreConfigFactory
     */
    protected $storeConfigFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfigMock;

    protected function setUp(): void
    {
        $this->storeConfigFactoryMock = $this->getMockBuilder(\Magento\Store\Model\Data\StoreConfigFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->storeCollectionFactoryMock = $this->getMockBuilder(
            \Magento\Store\Model\ResourceModel\Store\CollectionFactory::class
        )->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->scopeConfigMock = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);

        $this->model = new \Magento\Store\Model\Service\StoreConfigManager(
            $this->storeCollectionFactoryMock,
            $this->scopeConfigMock,
            $this->storeConfigFactoryMock
        );
    }

    /**
     * @param array $storeConfig
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getStoreMock(array $storeConfig)
    {
        $storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $storeMock->expects($this->once())
            ->method('getId')
            ->willReturn($storeConfig['id']);
        $storeMock->expects($this->any())
            ->method('getCode')
            ->willReturn($storeConfig['code']);
        $storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($storeConfig['website_id']);

        $urlMap = [
            [\Magento\Framework\UrlInterface::URL_TYPE_WEB, false, $storeConfig['base_url']],
            [\Magento\Framework\UrlInterface::URL_TYPE_WEB, true, $storeConfig['secure_base_url']],
            [\Magento\Framework\UrlInterface::URL_TYPE_LINK, false, $storeConfig['base_link_url']],
            [\Magento\Framework\UrlInterface::URL_TYPE_LINK, true, $storeConfig['secure_base_link_url']],
            [\Magento\Framework\UrlInterface::URL_TYPE_STATIC, false, $storeConfig['base_static_url']],
            [\Magento\Framework\UrlInterface::URL_TYPE_STATIC, true, $storeConfig['secure_base_static_url']],
            [\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, false, $storeConfig['base_media_url']],
            [\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, true, $storeConfig['secure_base_media_url']],
        ];
        $storeMock->expects($this->any())
            ->method('getBaseUrl')
            ->willReturnMap($urlMap);

        return $storeMock;
    }

    /**
     * @return \Magento\Store\Model\Data\StoreConfig
     */
    protected function createStoreConfigDataObject()
    {
        /** @var \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactoryMock */
        $extensionFactoryMock = $this->getMockBuilder(\Magento\Framework\Api\ExtensionAttributesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var \Magento\Framework\Api\AttributeValueFactory $attributeValueFactoryMock */
        $attributeValueFactoryMock = $this->getMockBuilder(\Magento\Framework\Api\AttributeValueFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $storeConfigDataObject = new \Magento\Store\Model\Data\StoreConfig(
            $extensionFactoryMock,
            $attributeValueFactoryMock,
            []
        );
        return $storeConfigDataObject;
    }

    public function testGetStoreConfigs()
    {
        $id = 1;
        $code = 'default';
        $websiteId = 1;
        $baseUrl = 'http://magento/base_url';
        $secureBaseUrl = 'https://magento/base_url';
        $baseLinkUrl = 'http://magento/base_url/links';
        $secureBaseLinkUrl = 'https://magento/base_url/links';
        $baseStaticUrl = 'http://magento/base_url/pub/static';
        $secureBaseStaticUrl = 'https://magento/base_url/static';
        $baseMediaUrl = 'http://magento/base_url/pub/media';
        $secureBaseMediaUrl = 'https://magento/base_url/pub/media';
        $locale = 'en_US';
        $timeZone = 'America/Los_Angeles';
        $baseCurrencyCode = 'USD';
        $defaultDisplayCurrencyCode = 'GBP';
        $weightUnit = 'lbs';

        $storeMocks = [];
        $storeConfigs = [
            'id' => $id,
            'code' => $code,
            'website_id' => $websiteId,
            'base_url' => $baseUrl,
            'secure_base_url' => $secureBaseUrl,
            'base_link_url' => $baseLinkUrl,
            'secure_base_link_url' => $secureBaseLinkUrl,
            'base_static_url' => $baseStaticUrl,
            'secure_base_static_url' => $secureBaseStaticUrl,
            'base_media_url' => $baseMediaUrl,
            'secure_base_media_url' => $secureBaseMediaUrl,
        ];
        $storeMocks[] = $this->getStoreMock($storeConfigs);

        $storeCollectionMock = $this->getMockBuilder(\Magento\Store\Model\ResourceModel\Store\Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $storeCollectionMock->expects($this->once())
            ->method('addFieldToFilter')
            ->with('code', ['in' => [$code]])
            ->willReturnSelf();
        $storeCollectionMock->expects($this->once())
            ->method('load')
            ->willReturn($storeMocks);
        $this->storeCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($storeCollectionMock);

        $storeConfigDataObject = $this->createStoreConfigDataObject();
        $this->storeConfigFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($storeConfigDataObject);
        $configValues = [
            ['general/locale/code', ScopeInterface::SCOPE_STORES, $code, $locale],
            ['currency/options/base', ScopeInterface::SCOPE_STORES, $code, $baseCurrencyCode],
            ['currency/options/default', ScopeInterface::SCOPE_STORES, $code, $defaultDisplayCurrencyCode],
            ['general/locale/timezone', ScopeInterface::SCOPE_STORES, $code, $timeZone],
            [\Magento\Directory\Helper\Data::XML_PATH_WEIGHT_UNIT, ScopeInterface::SCOPE_STORES, $code, $weightUnit]
        ];
        $this->scopeConfigMock->expects($this->any())
            ->method('getValue')
            ->willReturnMap($configValues);

        $result = $this->model->getStoreConfigs([$code]);

        $this->assertCount(1, $result);
        $this->assertEquals($id, $result[0]->getId());
        $this->assertEquals($code, $result[0]->getCode());
        $this->assertEquals($weightUnit, $result[0]->getWeightUnit());
        $this->assertEquals($baseUrl, $result[0]->getBaseUrl());
        $this->assertEquals($secureBaseUrl, $result[0]->getSecureBaseUrl());
        $this->assertEquals($baseLinkUrl, $result[0]->getBaseLinkUrl());
        $this->assertEquals($secureBaseLinkUrl, $result[0]->getSecureBaseLinkUrl());
        $this->assertEquals($baseStaticUrl, $result[0]->getBaseStaticUrl());
        $this->assertEquals($secureBaseStaticUrl, $result[0]->getSecureBaseStaticUrl());
        $this->assertEquals($baseMediaUrl, $result[0]->getBaseMediaUrl());
        $this->assertEquals($secureBaseMediaUrl, $result[0]->getSecureBaseMediaUrl());

        $this->assertEquals($timeZone, $result[0]->getTimezone());
        $this->assertEquals($locale, $result[0]->getLocale());
        $this->assertEquals($baseCurrencyCode, $result[0]->getBaseCurrencyCode());
        $this->assertEquals($defaultDisplayCurrencyCode, $result[0]->getDefaultDisplayCurrencyCode());
    }
}
