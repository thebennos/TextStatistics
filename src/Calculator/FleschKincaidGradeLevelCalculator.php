<?php
/**
 * Copyright (c) Andreas Heigl<andreas@heigl.org>
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @since     12.10.2016
 * @link      http://github.com/heiglandreas/org.heigl.TextStatistics
 */

namespace Org_Heigl\TextStatistics\Calculator;

use Org_Heigl\TextStatistics\Text;

/**
 * Class FleschReadingEaseCalculator
 *
 * This class provides ways to calculate the FleschReadingEase-Index.
 *
 * @see https://de.wikipedia.org/wiki/Lesbarkeitsindex
 * @package Org_Heigl\TextStatistics\Calculator
 */
class FleschKincaidGradeLevelCalculator implements CalculatorInterface
{
    protected $averageSentenceLengthCalculator;

    protected $averageSyllablesPerWordCalculator;

    public function __construct(
        AverageSentenceLengthCalculator $averageSentenceLengthCalculator,
        AverageSyllablesPerWordCalculator $averageSyllablesPerWordCalculator
    ) {
        $this->averageSentenceLengthCalculator = $averageSentenceLengthCalculator;
        $this->averageSyllablesPerWordCalculator = $averageSyllablesPerWordCalculator;
    }
    /**
     * Do the actual calculation of a statistic
     *
     * @param Text $text
     *
     * @see https://de.wikipedia.org/wiki/Lesbarkeitsindex
     * @return int
     */
    public function calculate(Text $text)
    {
        return (0.39 * $this->averageSentenceLengthCalculator->calculate($text)) +
               (11.8 * $this->averageSyllablesPerWordCalculator->calculate($text)) -
               15.59
            ;
    }
}
