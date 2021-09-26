<?php

namespace NerdGeneration\Macro\Parsers\Simple;

use NerdGeneration\Macro\Parsers\ParameterStringAbstract;

/**
 * Represents a string for Parsers\Simple
 * @author Mark Griffin
 */
class ParameterString extends ParameterStringAbstract {
    /**
     * Gets the code representation for this entry
     *
     * @return string
     */
    public function getCode(): string
    {
        return '"' . str_replace('"', '""', $this->value) . '"';
    }
}
