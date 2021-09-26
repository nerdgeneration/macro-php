<?php

namespace NerdGeneration\Macro\Parsers;

/**
 * Represents a constant and provides its own getCode() which is simply the name
 *
 * @author Mark Griffin
 */
class ParameterConstant extends ParameterAbstract
{
    /** @var array Pre-defined values */
    static protected array $binds = [];

    /** @var string Constant name */
    protected string $name;

    /** @var string Constant value */
    protected string $value;

    /**
     * Globally binds a name to a value
     *
     * @param string $name
     * @param string $value
     */
    static public function bind($name, $value)
    {
        self::$binds[$name] = $value;
    }

    /**
     * Sets up the constant name and applies the value binding if applicable
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        $this->value = '';
        if (isset(self::$binds[$name])) {
            $this->value = self::$binds[$name];
        }
    }

    /**
     * Checks if this name is globally bound to a value
     *
     * @return bool
     */
    public function isBound(): bool
    {
        return isset(self::$binds[$this->name]);
    }

    /**
     * Gets the constant name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the constant value
     *
     * @param string $value
     * @return ParameterConstant
     */
    public function setValue($value): static
    {
        $this->value = (string)$value;
        return $this;
    }

    /**
     * Gets the constant value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Gets the constant value via getValue()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * Gets the code representation for this entry
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->name;
    }
}
