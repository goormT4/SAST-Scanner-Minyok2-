<?php

declare (strict_types=1);
namespace WappoVendor\Doctrine\Inflector\Rules\NorwegianBokmal;

use WappoVendor\Doctrine\Inflector\GenericLanguageInflectorFactory;
use WappoVendor\Doctrine\Inflector\Rules\Ruleset;
final class InflectorFactory extends GenericLanguageInflectorFactory
{
    protected function getSingularRuleset() : Ruleset
    {
        return Rules::getSingularRuleset();
    }
    protected function getPluralRuleset() : Ruleset
    {
        return Rules::getPluralRuleset();
    }
}
