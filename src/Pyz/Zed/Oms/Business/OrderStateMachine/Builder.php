<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\OrderStateMachine;

use Pyz\Zed\Oms\Business\Process\EventInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\Builder as SprykerBuilder;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Process\StateInterface;
use Spryker\Zed\Oms\Business\Process\TransitionInterface;

class Builder extends SprykerBuilder
{
    /**
     * @var \Pyz\Zed\Oms\Business\Process\EventInterface
     */
    protected $event;

    /**
     * @param \Pyz\Zed\Oms\Business\Process\EventInterface $event
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param mixed $processDefinitionLocation
     * @param string $subProcessPrefixDelimiter
     */
    public function __construct(
        EventInterface $event,
        StateInterface $state,
        TransitionInterface $transition,
        ProcessInterface $process,
        $processDefinitionLocation,
        $subProcessPrefixDelimiter = ' - '
    ) {
        parent::__construct(
            $event,
            $state,
            $transition,
            $process,
            $processDefinitionLocation,
            $subProcessPrefixDelimiter
        );
    }

    /**
     * @return array
     */
    protected function createEvents()
    {
        $eventMap = [];

        foreach ($this->rootElement as $xmlProcess) {
            if (!isset($xmlProcess->events)) {
                continue;
            }

            $xmlEvents = $xmlProcess->events->children();
            foreach ($xmlEvents as $xmlEvent) {
                $event = clone $this->event;
                $eventId = $this->getAttributeString($xmlEvent, 'name');
                $event->setCommand($this->getAttributeString($xmlEvent, 'command'));
                $event->setManual($this->getAttributeBoolean($xmlEvent, 'manual'));
                $event->setOnEnter($this->getAttributeBoolean($xmlEvent, 'onEnter'));
                $event->setTimeout($this->getAttributeString($xmlEvent, 'timeout'));
                /**
                 * Only line below was added, the rest of the code in this function is the same as in the parent class
                 */
                $event->setCondition($this->getAttributeString($xmlEvent, 'condition'));
                $event->setTimeoutProcessor($this->getAttributeString($xmlEvent, 'timeoutProcessor'));
                if ($eventId === null) {
                    continue;
                }

                $event->setName($eventId);
                $eventMap[$event->getName()] = $event;
            }
        }

        return $eventMap;
    }
}
