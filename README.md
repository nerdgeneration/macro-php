# Macro
*Mark Griffin, Nerd Generation*


This is basically a macro language, but in native PHP. It is easy to extend and inspect: in fact
it is also a capable configuration language, suitable for API requests and more.


These classes provide simple parsing of function calls, strings and constants. The parsers are
responsible for converting a string into ParameterAbstract descendants. The Machine is responsible
for looping over the ParameterAbstract descendants and folding them into string values. The State
contains any variable or function managed data which is kept for the duration of the Machine
(think global variables). The Library is responsible for exposing functionality to the Machine in
the form of name-spaced function names mapped to native code.


### Machine:

This class takes Library objects and an optional State object. It then takes at least one
ParserAbstract descendant which provides the structured statements for execution. The Machine loops
over each statement and recursively folds it into a string value, from the deepest value until only
a string per statement remains. As the folding takes place, constants are referred to the State
object for resolving (though unresolved entries can have bound values via the ParameterConstant
class), and functions are referred to the Library for native code execution.


### State:

This class is at the core a very basic getter/setter value store, but can be extended to provide
more advanced constant resolver features. Simply extend the State class and override the get method
to provide access to other values.


### Library:

This class exposes a number of named functions with a name-space. Each call receives a number of
string values which represent the folded data that has been converted or executed as necessary.
The call should return a string value, or throw an exception. Multiple Library objects can be
used to provide multiple name-spaces and additional functionality.


### Parsers (folder):

The folder contains one or more folders, each representing a ParserAbstract descendant and any
support classes. The class is responsible for taking and parsing code, converting it into a series
of ParameterAbstract descendant objects. The Simple parser should be a reasonable example, even if
the parse method is a little complex.


### Parsers\Simple:

This parser handles code in the form NS:Function(), Constant or "String". Functions may be nested.
Strings may take any value, and quotes are doubled to escape. Operators (arithmetic, boolean,
logic etc) are not supported. Whitespace is unimportant.

Here's an example of PHP code written in Simple notation, assuming String:Lower and String:Join are
defined in a Library:

PHP:

    return strtolower('Quote1: ", Quote2: \'') . MY_CONSTANT;

Simple:

    String:Join(String:Lower("Quote1: "", Quote2: '"), MyConstant)

Statements are separated by commas. Strings and constants are statements in their own right. The
following are valid two statements equalling "Hello", "world":

    "Hello", String:Lower("World")

This parser is designed for speed of processing and reduced escaping in URLs.


### To do:
- Comprehensive standard library (in progress)
- Comprehensive unit tests (in progress)
- Composer library
- Convert to Javascript, Python, Go, Perl and more