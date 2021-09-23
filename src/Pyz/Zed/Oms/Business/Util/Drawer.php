<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business\Util;

use Spryker\Zed\Oms\Business\Process\TransitionInterface;
use Spryker\Zed\Oms\Business\Util\Drawer as SprykerDrawer;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

class Drawer extends SprykerDrawer
{
    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     * @param string[] $label
     *
     * @return string[]
     */
    protected function addEdgeEventText(TransitionInterface $transition, $label)
    {
        if ($transition->hasEvent()) {
            $event = $transition->getEvent();

            if ($event->isOnEnter()) {
                $label[] = '<b>' . $event->getName() . ' (on enter)</b>';
            } else {
                $label[] = '<b>' . $event->getName() . '</b>';
            }

            if ($event->hasTimeout()) {
                $label[] = 'timeout: ' . $event->getTimeout();
            }

            if ($event->hasCommand()) {
                $commandLabel = 'c:' . $event->getCommand();

                if ($this->inCollection($this->commands, $event->getCommand())) {
                    $commandModel = $this->commands->get($event->getCommand());
                    if ($commandModel instanceof CommandByOrderInterface) {
                        $commandLabel .= ' (by order)';
                    } else {
                        $commandLabel .= ' (by item)';
                    }
                } else {
                    $commandLabel .= ' ' . $this->notImplemented;
                }
                $label[] = $commandLabel;
            }

            if ($event->hasTimeoutProcessor()) {
                $label[] = sprintf('timeout processor: %s', $this->getTimeoutProcessorLabel($event));
            }

            if ($event->isManual()) {
                /** @var \Pyz\Zed\Oms\Business\Process\EventInterface $event */
                if ($event->getCondition()) {
                    $label[] = "conditionally manually executable (condition: {$event->getCondition()})";
                } else {
                    $label[] = 'manually executable';
                }
            }
        } else {
            $label[] = '&infin;';
        }

        return $label;
    }
}
