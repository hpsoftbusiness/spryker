<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business;

use Pyz\Zed\Oms\Business\Calculator\InitiationTimeoutCalculator;
use Pyz\Zed\Oms\Business\Calculator\TimeoutProcessorTimeoutCalculatorInterface;
use Pyz\Zed\Oms\Business\Mail\MailHandler;
use Pyz\Zed\Oms\Business\OrderStateMachine\Builder;
use Pyz\Zed\Oms\Business\OrderStateMachine\Finder;
use Pyz\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReader;
use Pyz\Zed\Oms\Business\OrderStateMachine\OrderStateMachine;
use Pyz\Zed\Oms\Business\Process\Event;
use Pyz\Zed\Oms\Business\Util\ConditionalEventVisibilityChecker;
use Pyz\Zed\Oms\Business\Util\ConditionalEventVisibilityCheckerInterface;
use Pyz\Zed\Oms\Business\Util\Drawer;
use Pyz\Zed\Oms\OmsDependencyProvider;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Oms\Business\OmsBusinessFactory as SprykerOmsBusinessFactory;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReaderInterface;

class OmsBusinessFactory extends SprykerOmsBusinessFactory
{
    use LoggerTrait;

    /**
     * @return \Pyz\Zed\Oms\Business\Calculator\TimeoutProcessorTimeoutCalculatorInterface
     */
    public function createInitiationTimeoutCalculator(): TimeoutProcessorTimeoutCalculatorInterface
    {
        return new InitiationTimeoutCalculator();
    }

    /**
     * @return \Pyz\Zed\Oms\Business\Mail\MailHandler
     */
    public function createMailHandler(): MailHandler
    {
        return new MailHandler(
            $this->getSalesFacade(),
            $this->getMailFacade(),
            $this->getOmsOrderMailExpanderPlugins(),
            $this->getLogger()
        );
    }

    /**
     * @param array $logContext
     *
     * @return \Pyz\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface
     */
    public function createOrderStateMachine(array $logContext = [])
    {
        return new OrderStateMachine(
            $this->getQueryContainer(),
            $this->createOrderStateMachineBuilder(),
            $this->createUtilTransitionLog($logContext),
            $this->createOrderStateMachineTimeout(),
            $this->createUtilReadOnlyArrayObject($this->getConfig()->getActiveProcesses()),
            $this->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS),
            $this->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),
            $this->createUtilReservation(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    public function createOrderStateMachineBuilder()
    {
        return new Builder(
            $this->createProcessEvent(),
            $this->createProcessState(),
            $this->createProcessTransition(),
            $this->createProcessProcess(),
            $this->getConfig()->getProcessDefinitionLocation(),
            $this->getConfig()->getSubProcessPrefixDelimiter()
        );
    }

    /**
     * @return \Pyz\Zed\Oms\Business\Process\EventInterface
     */
    public function createProcessEvent()
    {
        return new Event();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface
     */
    public function createOrderStateMachineFinder()
    {
        return new Finder(
            $this->getQueryContainer(),
            $this->createOrderStateMachineBuilder(),
            $this->getConfig()->getActiveProcesses(),
            $this->createConditionalEventVisibilityChecker()
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Util\DrawerInterface
     */
    public function createUtilDrawer()
    {
        return new Drawer(
            $this->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),
            $this->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS),
            $this->getGraph()->init('Statemachine', $this->getConfig()->getGraphDefaults(), true, false),
            $this->getUtilTextService(),
            $this->createTimeoutProcessorCollection()
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReaderInterface
     */
    public function createOrderItemManualEventReader(): OrderItemManualEventReaderInterface
    {
        return new OrderItemManualEventReader(
            $this->createOrderStateMachineBuilder(),
            $this->getQueryContainer(),
            $this->createConditionalEventVisibilityChecker()
        );
    }

    /**
     * @return \Pyz\Zed\Oms\Business\Util\ConditionalEventVisibilityCheckerInterface
     */
    public function createConditionalEventVisibilityChecker(): ConditionalEventVisibilityCheckerInterface
    {
        return new ConditionalEventVisibilityChecker(
            $this->createOrderStateMachine()
        );
    }
}
