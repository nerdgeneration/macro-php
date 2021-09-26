<?php

namespace NerdGeneration\Macro;

/**
 * Exception representing errors within Library code. All libraries should throw this type.
 *
 * @author Mark Griffin
 */
class LibraryException extends RuntimeException {
    /**
     * Creates an appropriate message including the library namespace and method
     *
     * @param LibraryAbstract $library
     * @param string $method
     * @param string $message
     */
    public function __construct(LibraryAbstract $library, string $method, string $message)
    {
        // Extract the namespace
        $namespace = $library->getNamespace();

        // Extract method from $method (which includes namespace and class)
        if (str_contains($method, '::')) {
            list(, $method) = explode('::', $method, 2);
        }

        // Strip out the "call" prefix
        if (str_starts_with($method, 'call')) {
            $method = substr($method, 4);
        }

        // Complete the exception setup with a useful message
        parent::__construct(
            "Library $namespace:$method failed: $message"
        );
    }
}