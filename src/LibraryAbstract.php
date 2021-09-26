<?php

namespace NerdGeneration\Macro;

/**
 * Library namespace and call support. Defaults to transparently call any descendant methods with
 * "call" prefixed to the name. String:Lower, if this were the String library, would call the
 * method callLower()
 *
 * @author Mark Griffin
 */
abstract class LibraryAbstract {
    /** @var string Library namespace in PascalCase */
    protected string $namespace;

    /** @var Machine */
    protected Machine $machine;

    /**
     * Sets up the library for a specific machine, which allows access to the current state object
     *
     * @param Machine $machine
     */
    public function __construct(Machine $machine)
    {
        $this->machine = $machine;
    }

    /**
     * Get the library namespace in PascalCase
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Calls call$name on descendant objects with the parameters, and returns the string result.
     * Throws a RuntimeException if the method does not exist.
     *
     * @param string $name
     * @param array $parameters
     * @return string
     * @throws RuntimeException
     */
    public function call(string $name, array $parameters): string
    {
        $method = 'call' . $name;

        if (method_exists($this, $method)) {
            return $this->$method($parameters);
        } else {
            throw new RuntimeException('Method not found: ' . $this->namespace . ':' . $name);
        }
    }
}
