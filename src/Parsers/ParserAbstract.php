<?php

namespace NerdGeneration\Macro\Parsers;

/**
 * Defines the basic behaviour of a parser
 * @author Mark Griffin
 */
abstract class ParserAbstract
{
    /** @var ParameterAbstract[] */
    protected array $statements;

    /**
     * Sets up the object with optional starting code
     *
     * @param string|null $call
     */
    public function __construct(string $call = '')
    {
        $this->statements = [];

        if ($call !== '') {
            $this->addCode($call);
        }
    }

    /**
     * Parses and adds a series of statements from a string
     *
     * @param string $code
     * @return ParserAbstract
     */
    abstract public function addCode(string $code): ParserAbstract;

    /**
     * Adds a pre-constructed statement
     *
     * @param ParameterAbstract $statement
     * @return ParserAbstract
     */
    public function addStatement(ParameterAbstract $statement): static
    {
        $this->statements[] = $statement;
        return $this;
    }

    /**
     * Adds pre-constructed statements
     *
     * @param ParameterAbstract[] $statements
     * @return ParserAbstract
     */
    public function addStatements(array $statements): static
    {
        foreach ($statements as $statement) {
            $this->addStatement($statement);
        }
        return $this;
    }

    /**
     * Gets all statements
     *
     * @return ParameterAbstract[]
     */
    public function getStatements(): array
    {
        return $this->statements;
    }

    /**
     * Converts the statements into a string which should result in the same statement objects
     * if parsed.
     *
     * @return string
     */
    abstract public function getCode(): string;
}
