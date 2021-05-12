<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Service\UtilText;

use Codeception\Test\Unit;
use Pyz\Service\UtilText\UtilTextServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Service
 * @group UtilText
 * @group UtilTextServiceTest
 * Add your own group annotations below this line
 */
class UtilTextServiceTest extends Unit
{
    /**
     * @var \PyzTest\Service\UtilText\UtilTextServiceTester
     */
    protected $tester;

    /**
     * @var \Pyz\Service\UtilText\UtilTextServiceInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->getService();
    }

    /**
     * @dataProvider provideCamelCaseToStringCaseData
     *
     * @param string $inputString
     * @param string $expectedResult
     *
     * @return void
     */
    public function testCamelCasedStringConvertedToSnakeCased(string $inputString, string $expectedResult): void
    {
        $result = $this->sut->camelCaseToSnakeCase($inputString);

        self::assertEquals($expectedResult, $result);
    }

    /**
     * @return string[][]
     */
    public function provideCamelCaseToStringCaseData(): array
    {
        return [
            ['snakeCased', 'snake_cased'],
            ['snakeABCased', 'snake_a_b_cased'],
            ['SnakeCased', 'snake_cased'],
        ];
    }

    /**
     * @return \Pyz\Service\UtilText\UtilTextServiceInterface
     */
    private function getService(): UtilTextServiceInterface
    {
        return $this->tester->getLocator()->utilText()->service();
    }
}
