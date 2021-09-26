<?php

namespace NerdGeneration\Macro\Parsers;

/**
 * Represents a string
 * @author Mark Griffin
 */
abstract class ParameterStringAbstract extends ParameterAbstract
{
    /** @var string String value */
    protected string $value;

    /**
     * Sets up the string value
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Sets the string value
     *
     * @param string $value
     * @return ParameterStringAbstract
     */
    public function setValue(string $value): static
    {
        $this->value = (string)$value;
        return $this;
    }

    /**
     * Gets the string value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Gets the string value value via getValue()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }
}
