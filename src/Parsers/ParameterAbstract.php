<?php

namespace NerdGeneration\Macro\Parsers;

/**
 * Base parameter class
 *
 * @author Mark Griffin
 */
abstract class ParameterAbstract
{
    /**
     * Gets the code representation for this entry
     *
     * @return string
     */
    abstract public function getCode(): string;
}
