<?php

namespace NerdGeneration\Macro;

use NerdGeneration\Macro\Parsers\ParameterAbstract;
use NerdGeneration\Macro\Parsers\ParameterConstant;
use NerdGeneration\Macro\Parsers\ParameterFunctionAbstract;
use NerdGeneration\Macro\Parsers\ParameterStringAbstract;
use NerdGeneration\Macro\Parsers\ParserAbstract;

class Machine {
    /** @var State */
    protected State $state;

    /** @var LibraryAbstract[] */
    protected array $library;

    /**
     * Sets up the object with optional libraries and state
     *
     * @param State|null $state
     */
    public function __construct(State $state = null)
    {
        $this->library = [];

        // Add the State if given, or an empty one
        if ($state instanceof State) {
            $this->setState($state);
        } else {
            $this->setState(new State());
        }
    }

    /**
     * Adds the standard libraries (text, number and date)
     *
     * @return Machine
     */
    public function addStandardLibrary(): static
    {
        foreach (['Text', 'Number', 'Date', 'Boolean'] as $library) {
            $class = '\NerdGeneration\Macro\StandardLibrary\\' . $library;
            $this->addLibrary(new $class($this));
        }

        return $this;
    }

    /**
     * Adds a library
     *
     * @param LibraryAbstract $library
     * @return Machine
     */
    public function addLibrary(LibraryAbstract $library): static
    {
        $this->library[$library->getNamespace()] = $library;

        return $this;
    }

    /**
     * Sets the state object
     *
     * @param State $state
     * @return Machine
     */
    public function setState(State $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Gets the state object
     *
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * Recursive function that resolves string and constant types via State, and function types
     * recursively via LibraryAbstract
     *
     * @param ParameterAbstract $parameter
     * @throws RuntimeException
     * @return string
     */
    protected function resolveStatement(ParameterAbstract $parameter): string
    {
        if ($parameter instanceof ParameterFunctionAbstract) {
            // Function, resolve parameters and parse via LibraryAbstract
            $functionName = $parameter->getName();

            // Resolve each function parameter into a string
            $functionParameters = [];
            foreach ($parameter->getParameters() as $functionParameter) {
                $functionParameters[] = $this->resolveStatement($functionParameter);
            }

            // Check for namespace
            $functionNamespace = '';
            if (str_contains($functionName, ':')) {
                list($functionNamespace, $functionName) = explode(':', $functionName, 2);
            }

            // Find the library and call it
            if (!isset($this->library[$functionNamespace])) {
                throw new RuntimeException('Unknown namespace: ' . $functionNamespace);
            }

            try {
                $value = $this->library[$functionNamespace]->call(
                    $functionName,
                    $functionParameters
                );
            } catch (RuntimeException $e) {
                // Add this call to the stack
                $e->addRuntimeStack($parameter);
                throw $e;
            }

        } elseif ($parameter instanceof ParameterStringAbstract) {
            // String, simple value only
            $value = $parameter->getValue();

        } elseif ($parameter instanceof ParameterConstant) {
            // Constant, dereference via State
            $value = $this->state->get($parameter->getName());
            if (!is_null($value)) {
                $parameter->setValue($value);
            }
            $value = $parameter->getValue();

        } else {
            throw new RuntimeException('Unknown parameter type (internal error)');
        }

        return (string)$value;
    }

    /**
     * Executes all statements in the given parser and returns a flat array of strings
     *
     * @param ParserAbstract $parser
     * @return string[]
     * @throws RuntimeException
     */
    public function execute(ParserAbstract $parser): array
    {
        $statements = $parser->getStatements();
        $flat = [];

        foreach ($statements as $statement) {
            $flat[] = $this->resolveStatement($statement);
        }

        return $flat;
    }
}
