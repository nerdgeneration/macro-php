<?php

namespace NerdGeneration\Macro\Parsers;

/**
 * Represents a function call
 *
 * @author Mark Griffin
 */
abstract class ParameterFunctionAbstract extends ParameterAbstract
{
    /** @var string Function name */
    protected string $name;

    /** @var ParameterAbstract[] Function parameters */
    protected array $parameters;

    /**
     * Sets up the function call detail
     *
     * @param string $name
     * @param array $parameters
     */
    final public function __construct(string $name, array $parameters)
    {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    /**
     * Gets the function name
     *
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the function parameters
     *
     * @return ParameterAbstract[]
     */
    final public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Adds a pre-constructed parameter
     *
     * @param ParameterAbstract $parameter
     * @return ParameterFunctionAbstract
     */
    final public function addParameter(ParameterAbstract $parameter): static
    {
        $this->parameters[] = $parameter;
        return $this;
    }

    /**
     * Adds multiple pre-constructed parameters
     *
     * @param ParameterAbstract[] $parameters
     * @return ParameterFunctionAbstract
     */
    final public function addParameters(array $parameters): static
    {
        foreach ($parameters as $parameter) {
            $this->addParameter($parameter);
        }
        return $this;
    }
}
