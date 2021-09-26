<?php

namespace NerdGeneration\Macro;
use NerdGeneration\Macro\Parsers\ParameterFunctionAbstract;

/**
 * Exception representing execution errors - missing functions, data incompatibility etc
 *
 * @author Mark Griffin
 */
class RuntimeException extends \Exception {
    /** @var ParameterFunctionAbstract[] */
    protected array $stack = [];

    /**
     * Adds a call to the runtime stack
     *
     * @param ParameterFunctionAbstract $function
     */
    public function addRuntimeStack(ParameterFunctionAbstract $function)
    {
        $this->stack[] = $function;
    }

    /**
     * Builds a stack trace for the Macro, in a similar format to \Exception::getTrace
     *
     * @return array
     */
    public function getRuntimeTrace(): array
    {
        $trace = [];
        foreach ($this->stack as $call) {
            // Build function parameters as strings
            $args = [];
            foreach ($call->getParameters() as $parameter) {
                $args[] = $parameter->getCode();
            }

            // Add detail to trace
            $trace[] = [
                'file' => null,
                'line' => 1,
                'function' => $call->getName(),
                'args' => $args
            ];
        }

        return $trace;
    }

    /**
     * Builds a stack trace for the Macro as a string, in a similar format to
     * \Exception::getTraceAsString
     *
     * @return string
     */
    public function getRuntimeTraceAsString(): string
    {
        $i = 0;
        $result = '';

        foreach ($this->stack as $trace) {
            $result .= '#' . $i . ': ' . $trace->getCode() . "\n";
            $i++;
        }

        return $result;
    }

    /**
     * Builds an exception message similar to the PHP native one, but includes a stack trace for
     * the Macro too
     *
     * @return string
     */
    public function __toString()
    {
        return
            // Class: Message
            get_class($this) . ': ' .
            $this->getMessage() . "\n" .

            // Trace: Macro
            "Macro Stack Trace:\n" .
            $this->getRuntimeTraceAsString() .

            // Trace: PHP
            "Stack trace:\n" .
            $this->getTraceAsString();
    }
}
