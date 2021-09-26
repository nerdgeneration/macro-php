<?php

namespace NerdGeneration\Macro\Parsers\Simple;

use NerdGeneration\Macro\Parsers\ParameterConstant;
use NerdGeneration\Macro\Parsers\ParserAbstract;
use NerdGeneration\Macro\Parsers\ParserException;
use NerdGeneration\Macro\Parsers\ParameterAbstract;

/**
 * Simple parser. Supports nested function calls, strings and constants only. Statements and values
 * are comma separated. Strings and constants are valid stand-alone statements.
 *
 * Quoted string:
 *      "Quoted"
 *      "Quotes "" Inside"
 *
 * Constant:
 *      MyConstant
 *
 * Function:
 *      MyFunction()
 *
 * Nested function with namespace and string and function parameter:
 *      Foo:Function(Bar:Function2(Constant, "String"))
 *
 * @author Mark Griffin
 */
class Parser extends ParserAbstract {
    const TOKEN_CONST = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_:.';
    const TOKEN_WHITESPACE = "\r\n\t ";

    /**
     * Parses a string of code into constituent ParameterAbstract descendants
     *
     * @param string $code
     * @param int &$pos
     * @return ParameterAbstract[]
     * @throws ParserException
     */
    protected function parse($code, &$pos)
    {
        $parameters = [];

        // Skip whitespace, pre statements
        $pos += strspn($code, self::TOKEN_WHITESPACE, $pos);

        do {
            if (substr($code, $pos, 1) == '"') {
                // Quotes
                $quoteLength = 0;
                $quoteEnd = false;
                while (!$quoteEnd) {
                    $quoteLength += strcspn($code, '"', $pos + $quoteLength + 1) + 2;

                    // Quote ends when there's no quote following a quote
                    $quoteEnd = substr($code, $pos + $quoteLength, 1) != '"';

                    // Check we're not at the end of the string
                    if (!$quoteEnd && ($pos + $quoteLength >= strlen($code))) {
                        throw new ParserException('Unterminated string at ' . $pos);
                    }
                }

                // String still contains all quote symbols, strip and save
                $string = str_replace('""', '"', substr($code, $pos + 1, $quoteLength - 2));
                $pos += $quoteLength;
                $parameters[] = new ParameterString($string);

            } else {
                // Function or Constant
                $constLength = strspn($code, self::TOKEN_CONST, $pos);
                $const = substr($code, $pos, $constLength);

                $pos += $constLength;
                if (substr($code, $pos, 1) == '(') {
                    // Recursive call
                    $pos++;
                    $parameters[] = new ParameterFunction($const, $this->parse($code, $pos));

                    if (substr($code, $pos, 1) == ')') {
                        $pos++;
                    } else {
                        throw new ParserException('Expected ) at ' . $pos);
                    }
                } elseif (strlen($const)) {
                    // Constant
                    $parameters[] = new ParameterConstant($const);
                } // else no value
            }

            // Skip whitespace, pre comma
            $pos += strspn($code, self::TOKEN_WHITESPACE, $pos);

            // Break out if there are no more comma separated parameters
            if (substr($code, $pos, 1) != ',') {
                break;
            }

            // Move past the comma separator
            $pos++;

            // Skip whitespace, post comma
            $pos += strspn($code, self::TOKEN_WHITESPACE, $pos);

        } while (true);

        if (($pos == strlen($code)) || (substr($code, $pos, 1) == ')')) {
            return $parameters;
        } else {
            throw new ParserException('Expected ) or end of string at ' . $pos);
        }
    }

    /**
     * Parses and adds a series of statements from a string
     *
     * @param string $code
     * @return Parser
     * @throws ParserException
     */
    public function addCode(string $code): ParserAbstract
    {
        $pos = 0;
        $this->statements = array_merge($this->statements, $this->parse($code, $pos));
        return $this;
    }

    /**
     * Converts the statements into a string which should result in the same statement objects
     * if parsed.
     *
     * @return string
     */
    public function getCode(): string
    {
        $statements = [];
        foreach ($this->statements as $statement) {
            $statements[] = $statement->getCode();
        }
        return implode(', ', $statements);
    }
}
