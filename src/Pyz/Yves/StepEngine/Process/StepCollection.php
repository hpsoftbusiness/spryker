<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\StepEngine\Process;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Spryker\Yves\StepEngine\Process\StepCollection as SprykerStepCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StepCollection extends SprykerStepCollection
{
    /**
     * @var string[]
     */
    protected $restrictedRoutesForErrorStep = [];

    /**
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param string $errorRoute
     * @param string[] $restrictedRoutesForErrorStep
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        $errorRoute,
        array $restrictedRoutesForErrorStep
    ) {
        parent::__construct($urlGenerator, $errorRoute);

        $this->restrictedRoutesForErrorStep = $restrictedRoutesForErrorStep;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    public function getCurrentStep(Request $request, AbstractTransfer $dataTransfer)
    {
        foreach ($this->steps as $step) {
            if ($this->isRouteMustBeSkipped($request, $step)) {
                continue;
            }

            if (!$step->postCondition($dataTransfer) || $request->get('_route') === $step->getStepRoute()) {
                return $step;
            }

            $this->completedSteps[] = $step;
        }

        return end($this->completedSteps);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $step
     *
     * @return bool
     */
    protected function isRouteMustBeSkipped(Request $request, StepInterface $step): bool
    {
        if (in_array($step->getStepRoute(), $this->restrictedRoutesForErrorStep)
            && $request->get('_route') === $this->errorRoute
        ) {
            return true;
        }

        return false;
    }
}
