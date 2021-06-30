<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CustomerPage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\SsoAccessTokenBuilder;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Pyz\Client\Sso\Client\Mapper\CustomerInformationMapper;
use Pyz\Client\Sso\Client\Mapper\CustomerInformationMapperInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CustomerPageProcessTester extends Actor
{
    use _generated\CustomerPageProcessTesterActions;

    /**
     * @return \Pyz\Client\Sso\Client\Mapper\CustomerInformationMapperInterface
     */
    public function getCustomerInformationMapper(): CustomerInformationMapperInterface
    {
        return new CustomerInformationMapper();
    }

    /**
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getSsoAccessTokenTransfer(): SsoAccessTokenTransfer
    {
        return (new SsoAccessTokenBuilder([
            SsoAccessTokenTransfer::ACCESS_TOKEN => 'test_access_token',
            SsoAccessTokenTransfer::EXPIRES_IN => 1800,
            SsoAccessTokenTransfer::TOKEN_TYPE => 'Bearer',
            SsoAccessTokenTransfer::ID_TOKEN => 12,
            SsoAccessTokenTransfer::REFRESH_TOKEN => 'test_refresh_token',
        ]))
            ->build();
    }

    /**
     * @param string $name
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup
     */
    public function haveCustomerGroupByName(string $name)
    {
        $customerGroup = SpyCustomerGroupQuery::create()
            ->filterByName($name)
            ->findOneOrCreate();

        if (!$customerGroup->getIdCustomerGroup()) {
            $customerGroup->save();
        }

        return $customerGroup;
    }
}
