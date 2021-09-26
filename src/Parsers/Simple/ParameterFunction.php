<?php

namespace NerdGeneration\Macro\Parsers\Simple;

use NerdGeneration\Macro\Parsers\ParameterFunctionAbstract;

/**
 * Represents a function call for Parsers\Simple
 * @author Mark Griffin
 */
class ParameterFunction extends ParameterFunctionAbstract {
    /**
     * Gets the code representation for this entry
     *
     * @return string
     */
    public function getCode(): string
    {
        $parameters = [];
        foreach ($this->parameters as $parameter) {
            $parameters[] = $parameter->getCode();
        }

        return $this->name . '(' . implode(', ', $parameters) . ')';
    }
}
