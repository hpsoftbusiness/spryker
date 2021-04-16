<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Service\ProductAffiliate;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Service\ProductAffiliate\Generator\ProductAffiliateLinkGenerator;
use Pyz\Service\ProductAffiliate\ProductAffiliateServiceInterface;
use Pyz\Shared\ProductAffiliate\ProductAffiliateConstants;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Service
 * @group ProductAffiliate
 * @group ProductAffiliateServiceTest
 * Add your own group annotations below this line
 */
class ProductAffiliateServiceTest extends Unit
{
    private const MY_WORLD_CUSTOMER_NUMBER = '043.010.627.559';

    /**
     * @var \PyzTest\Service\ProductAffiliate\ProductAffiliateServiceTester
     */
    protected $tester;

    /**
     * @var \Pyz\Service\ProductAffiliate\ProductAffiliateServiceInterface
     */
    private $sut;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    private $customerTransfer;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->getProductAffiliateService();
        $this->tester->setConfig(
            ProductAffiliateConstants::TRACKING_URL_PATH,
            'https://test-click.myworld.com/spryker'
        );
        $this->customerTransfer = $this->createCustomerTransfer();
    }

    /**
     * @dataProvider provideProductAffiliateUrlData
     *
     * @param string $deeplink
     * @param string $affiliateNetwork
     * @param string $affiliateMerchantID
     * @param string $expectedTrackingLink
     *
     * @return void
     */
    public function testProductAffiliateTrackingUrlGeneration(
        string $deeplink,
        string $affiliateNetwork,
        string $affiliateMerchantID,
        string $expectedTrackingLink
    ): void {
        $affiliateData = [
            ProductAffiliateLinkGenerator::KEY_AFFILIATE_DEEPLINK => $deeplink,
            ProductAffiliateLinkGenerator::KEY_AFFILIATE_NETWORK => $affiliateNetwork,
            ProductAffiliateLinkGenerator::KEY_AFFILIATE_MERCHANT_ID => $affiliateMerchantID,
        ];

        $link = $this->sut->generateProductAffiliateTrackingUrl(
            $affiliateData,
            $this->customerTransfer
        );

        self::assertEquals(
            $expectedTrackingLink,
            $link
        );
    }

    /**
     * @return string[][]
     */
    public function provideProductAffiliateUrlData(): array
    {
        return [
            'AwinTrackingUrlGeneration' => [
                'https://www.awin1.com/pclick.php?p=27134543463&a=333979&m=10842',
                'awin',
                '',
                'https://test-click.myworld.com/spryker?customerNumber=043010627559&network=AW_Dach&AdvertiserId=10842&url=https%3A%2F%2Fwww.awin1.com%2Fpclick.php%3Fp%3D27134543463%26a%3D333979%26m%3D10842%26clickRef%3D%7BTrackingHash%7D',
            ],
            'ChineseanTrackingUrlGeneration' => [
                'https://www.chinesean.com/affiliate/clickBanner.do?wId=44330&pId=11395&targetURL=https%253A%252F%252Fwww.mdreams.com%252F',
                'chinesean',
                '',
                'https://test-click.myworld.com/spryker?customerNumber=043010627559&network=CHINESEAN_USD&AdvertiserId=11395&url=https%3A%2F%2Fwww.chinesean.com%2Faffiliate%2FclickBanner.do%3FwId%3D44330%26pId%3D11395%26targetURL%3Dhttps%253A%252F%252Fwww.mdreams.com%252F%26mId%3D%7BTrackingHash%7D',
            ],
            'WebgainsTrackingUrlGeneration' => [
                'https://track.webgains.com/click.html?wgcampaignid=140473&wgprogramid=1457&product=1&wglinkid=89524&productname=FRANCK+%26+FISCHER+Rucksack+Hilda+-+rot+%28Franck+and+Fischer%29&wgtarget=https://www.babyshop.de/Babybekleidung/Rucksaecke-und-Taschen/FRANCK-FISCHER-Rucksack-Hilda-rot::44714.html?refID=047-wg',
                'webgains',
                '',
                'https://test-click.myworld.com/spryker?customerNumber=043010627559&network=WEBGAINS&AdvertiserId=1457&url=https%3A%2F%2Ftrack.webgains.com%2Fclick.html%3Fwgcampaignid%3D140473%26wgprogramid%3D1457%26product%3D1%26wglinkid%3D89524%26productname%3DFRANCK+%26+FISCHER+Rucksack+Hilda+-+rot+%28Franck+and+Fischer%29%26wgtarget%3Dhttps%3A%2F%2Fwww.babyshop.de%2FBabybekleidung%2FRucksaecke-und-Taschen%2FFRANCK-FISCHER-Rucksack-Hilda-rot%3A%3A44714.html%3FrefID%3D047-wg%26clickRef%3D%7BTrackingHash%7D',
            ],
            'TrandeDoublerTrackingUrlGeneration' => [
                'http://pdt.tradedoubler.com/click?a(1616295)p(217737)prod(3419598179)ttid(5)url(https%3A%2F%2Fwww.hse.at%2Fdpl%2Fp%2Fproduct%2F434389%3Fmkt%3DLAFF%26utm_source%3D%5Btd_affiliate_id%5D%26utm_medium%3Dtd%26utm_campaign%3D0000-aff-td-product%26refID%3Dtd%2F%5Btd_affiliate_id%5D%2FKlick%26%5Btd_guid%5D)',
                'TradeDoubler',
                '',
                'https://test-click.myworld.com/spryker?customerNumber=043010627559&network=TDP_%7BcountryId%7D&AdvertiserId=217737&url=http%3A%2F%2Fpdt.tradedoubler.com%2Fclick%3Fa%281616295%29p%28217737%29prod%283419598179%29ttid%285%29url%28https%3A%2F%2Fwww.hse.at%2Fdpl%2Fp%2Fproduct%2F434389%3Fmkt%3DLAFF%26utm_source%3D%5Btd_affiliate_id%5D%26utm_medium%3Dtd%26utm_campaign%3D0000-aff-td-product%26refID%3Dtd%2F%5Btd_affiliate_id%5D%2FKlick%26%5Btd_guid%5D%29epi%28%7BTrackingHash%7D%29',
            ],
            'TradeTrackerTrackingUrlGeneration' => [
                'https://tc.tradetracker.net/?c=17819&m=914730&a=214266&u=https%3A%2F%2Fde.vidaxl.ch%2Fe%2F8718475948803%2F%3Futm_source%3Dvidaxl_tradetracker%26utm_medium%3Daffiliate%26utm_campaign%3Dch_de_webshop%26utm_term%3D8718475948803%26utm_content%3DBaby%26Kleinkind%3D%26affiliate_id%3Dtradetracker',
                'TradeTracker',
                '',
                'https://test-click.myworld.com/spryker?customerNumber=043010627559&network=TT_CH&AdvertiserId=17819&url=https%3A%2F%2Ftc.tradetracker.net%2F%3Fc%3D17819%26m%3D914730%26a%3D214266%26u%3Dhttps%3A%2F%2Fde.vidaxl.ch%2Fe%2F8718475948803%2F%3Futm_source%3Dvidaxl_tradetracker%26utm_medium%3Daffiliate%26utm_campaign%3Dch_de_webshop%26utm_term%3D8718475948803%26utm_content%3DBaby%26Kleinkind%3D%26affiliate_id%3Dtradetracker%26r%3D%7BTrackingHash%7D',
            ],
            'CJTrackingUrlGeneration' => [
                'https://www.dpbolvw.net/click-8270047-13427942?url=https%3A%2F%2Fwww.halloweencostumes.com.au%2Fteal-trick-or-treat-pumpkin.html%3Fmpid%3D220877',
                'CJ',
                '5549846',
                'https://test-click.myworld.com/spryker?customerNumber=043010627559&network=CJ_AU&AdvertiserId=5549846&url=https%3A%2F%2Fwww.dpbolvw.net%2Fclick-8270047-13427942%3Furl%3Dhttps%253A%252F%252Fwww.halloweencostumes.com.au%252Fteal-trick-or-treat-pumpkin.html%253Fmpid%253D220877%26SID%3D%7BTrackingHash%7D',
            ],
            'UnknownAffiliateNetworkReturnsDeeplink' => [
                'link',
                'unknown',
                '',
                'link',
            ],
        ];
    }

    /**
     * @return \Pyz\Service\ProductAffiliate\ProductAffiliateServiceInterface
     */
    private function getProductAffiliateService(): ProductAffiliateServiceInterface
    {
        return $this->tester->getLocator()->productAffiliate()->service();
    }

    /**
     * @param string $mywCustomerNumber
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    private function createCustomerTransfer(string $mywCustomerNumber = self::MY_WORLD_CUSTOMER_NUMBER): CustomerTransfer
    {
        return (new CustomerTransfer())->setMyWorldCustomerNumber($mywCustomerNumber);
    }
}
