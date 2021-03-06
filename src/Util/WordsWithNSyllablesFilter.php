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

namespace Org_Heigl\TextStatistics\Util;

use Org\Heigl\Hyphenator\Filter\Filter;
use Org\Heigl\Hyphenator\Tokenizer as t;

class WordsWithNSyllablesFilter extends Filter
{
    protected $minSyllables;

    public function __construct($minSyllables)
    {
        $this->minSyllables = $minSyllables;
    }

    /**
     * Run the filter over the given Token
     *
     * @param \Org\Heigl\Hyphenator\Tokenizer\TokenRegistry $tokens The registry
     *                                                              to apply the filter to
     *
     * @return \Org\Heigl\Hyphenator\Tokenizer\TokenRegistry
     */
    public function run(t\TokenRegistry $tokens)
    {
        foreach ($tokens as $token) {
            if (! $token instanceof t\WordToken) {
                continue;
            }
            $string = $token->getFilteredContent();
            $pattern = $token->getMergedPattern();
            $length  = $token->length();
            $lastOne = 0;
            $syllables = array();
            for ($i = 1; $i <= $length; $i++) {
                $currPattern = mb_substr($pattern, $i, 1);
                if ($i < $this->_options->getLeftMin()) {
                    continue;
                }
                if ($i > $length - $this->_options->getRightMin()) {
                    continue;
                }
                if (0 == $currPattern) {
                    continue;
                }
                if (0 === (int) $currPattern % 2) {
                    continue;
                }
                $sylable = mb_substr($string, $lastOne, $i-$lastOne);
                $lastOne = $i;
                $syllables[] = $sylable;
            }
            $syllables[] = mb_substr($string, $lastOne);
            $token->setHyphenatedContent([]);
            if (count($syllables) >= $this->minSyllables) {
                $token->setHyphenatedContent([$token->getFilteredContent()]);
                continue;
            }
        }

        return $tokens;
    }

    /**
     * Concatenate the given TokenRegistry to return one result
     *
     * @param \Org\Heigl\Hyphenator\Tokenizer\TokenRegistry $tokens The registry
     *                                                              to apply the filter to
     *
     * @return mixed
     */
    protected function _concatenate(t\TokenRegistry $tokens) // @codingStandardsIgnoreLine
    {
        $wordsWithNSyllables = [];
        foreach ($tokens as $token) {
            if (! $token instanceof t\WordToken) {
                continue;
            }
            if (! $token->getHyphenatedContent()) {
                continue;
            }

            $wordsWithNSyllables[] = $token->getFilteredContent();
        }

        return $wordsWithNSyllables;
    }
}
