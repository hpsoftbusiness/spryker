<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment;

use Codeception\Actor;
use Codeception\Scenario;
use Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorldQuery;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

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
class MyWorldPaymentBusinessTester extends Actor
{
    use _generated\MyWorldPaymentBusinessTesterActions;

    /**
     * @var \PyzTest\Zed\MyWorldPayment\DataHelper $dataHelper
     */
    public $dataHelper;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->dataHelper = new DataHelper();
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    public function getConfig(): MyWorldPaymentConfig
    {
        return $this->getModuleConfig();
    }

    /**
     * @return \Orm\Zed\MyWorldPayment\Persistence\PyzPaymentMyWorldQuery
     */
    public function createPaymentDataQuery(): PyzPaymentMyWorldQuery
    {
        return PyzPaymentMyWorldQuery::create();
    }
}
