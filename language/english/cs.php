<?php

declare(strict_types=1);

/*
You may not change or alter any portion of this comment or credits
of supporting developers from this source code or any supporting source code
which is considered copyrighted (c) material of the original comment or credit authors.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Xoops XoopsSecure module for xoops
 *
 * @copyright 2021 XOOPS Project (https://xoops.org)
 * @license   GPL 2.0 or later
 * @package   XoopsSecure
 * @since     1.0
 * @min_xoops 2.5.11
 * @author    Culex - Email:culex@culex.dk - Website:https://www.culex.dk
 * @ignore Language defines
 */

require_once __DIR__ . '/common.php';

// ---------------- Coding Standards ----------------

define("_CS_XOOPSSECURE_PHP_EXPCEPTION", "PHP Exception: %s.");
define("_CS_XOOPSSECURE_INDENTATION_TAB", "Tab indentation must not be used.");
define("_CS_XOOPSSECURE_INDENTATION_WHITESPACE", "Whitespace indentation must not be used.");
define("_CS_XOOPSSECURE_INDENTATION_LEVEL", "The indentation level must be %s but was %s.");
define("_CS_XOOPSSECURE_WRONG_OPEN_TAG", "The PHP open tag must be '<?php'.");
define("_CS_XOOPSSECURE_NO_SPACE_BEFORE_TOKEN", "Whitespace must not precede %s.");
define("_CS_XOOPSSECURE_NO_SPACE_AFTER_TOKEN", "Whitespace must not follow %s.");
define("_CS_XOOPSSECURE_SPACE_BEFORE_TOKEN", "Whitespace must precede %s.");
define("_CS_XOOPSSECURE_SPACE_AFTER_TOKEN", "Whitespace must follow %s.");
define("_CS_XOOPSSECURE_LEFT_CURLY_POS", "The block opening '{' must be on %s");
define("_CS_XOOPSSECURE_CS_NO_OPEN_CURLY", "A {} block must enclose the control statement %s.");
define("_CS_XOOPSSECURE_CS_STMT_ALIGNED_WITH_CURLY", "The block closure '}' must be on %s");
define("_CS_XOOPSSECURE_END_BLOCK_NEW_LINE", "The block closure '}' must be on a new line.");
define("_CS_XOOPSSECURE_CONSTANT_NAMING", "Constant %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_VARIABLE_NAMING", "Variable %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_LOCAL_VARIABLE_NAMING", "Local variable %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_MEMBER_VARIABLE_NAMING", "Member variable %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_FUNCTION_PARAMETER_NAMING", "Function parameter %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_TOPLEVEL_VARIABLE_NAMING", "Top level variable %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_FUNCNAME_SPACE_AFTER", "Whitespace must not be between the function %s and the opening brace '{'.");
define("_CS_XOOPSSECURE_PRIVATE_FUNCNAME_NAMING", "Private function %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_PROTECTED_FUNCNAME_NAMING", "Protected function %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_FUNCNAME_NAMING", "Function %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_FUNC_DEFAULTVALUE_ORDER", "All arguments with default values must be at the end of the block or statement.");
define("_CS_XOOPSSECURE_TYPE_FILE_NAME_MISMATCH", "Type name %s must match file name %s.");
define("_CS_XOOPSSECURE_CLASSNAME_NAMING", "Class %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_INTERFACENAME_NAMING", "Interface %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_FILENAME_NAMING", "File %s name should follow the pattern %s.");
define("_CS_XOOPSSECURE_NO_SHELL_COMMENTS", "Avoid Shell/Perl like comments.");
define("_CS_XOOPSSECURE_MISSING_DOCBLOCK", "The %s %s must have a docblock comment.");
define("_CS_XOOPSSECURE_LONG_LINE", "Line is too long. [%s/%s]");
define("_CS_XOOPSSECURE_PROHIBITED_FUNCTION", "The function %s must not be called.");
define("_CS_XOOPSSECURE_PROHIBITED_TOKEN", "Token %s must not be used.");
define("_CS_XOOPSSECURE_PROHIBITED_KEYWORD", "Keyword %s is not allowed.");
define("_CS_XOOPSSECURE_PROHIBITED_KEYWORD_REGEX", "Pattern %s is not allowed.");
define("_CS_XOOPSSECURE_CS_STMT_ON_NEW_LINE", "%s must be on the line after '}'");
define("_CS_XOOPSSECURE_END_FILE_INLINE_HTML", "Inline HTML must not be included at the end of the file.");
define("_CS_XOOPSSECURE_END_FILE_CLOSE_TAG", "A PHP close tag must not be included at the end of the file.");
define("_CS_XOOPSSECURE_SILENCED_ERROR", "Errors must not be silenced when calling a function.");
define("_CS_XOOPSSECURE_VARIABLE_INSIDE_STRING", "Encapsed variables must not be used inside a string.");
define("_CS_XOOPSSECURE_PASSING_REFERENCE", "Parameters must not be passed by reference.");
define("_CS_XOOPSSECURE_CYCLOMATIC_COMPLEXITY", "The Cyclomatic Complexity of function %s is too high. [%s/%s]");
define("_CS_XOOPSSECURE_NPATH_COMPLEXITY", "The NPath Complexity of the function %s is too high. [%s/%s]");
define("_CS_XOOPSSECURE_TODO", "TODO: %s");
define("_CS_XOOPSSECURE_GOTO", "The control statement 'goto' must not be used.");
define("_CS_XOOPSSECURE_CONTINUE", "The control statement 'continue' must not be used.");
define("_CS_XOOPSSECURE_CONSTRUCTOR_NAMING", "The constructor name must be %s.");
define("_CS_XOOPSSECURE_USE_BOOLEAN_OPERATORS_AND", "Boolean operators (&&) must be used instead of logical operators (AND).");
define("_CS_XOOPSSECURE_USE_BOOLEAN_OPERATORS_OR", "Boolean operators (||) must be used instead of logical operators (OR).");
define("_CS_XOOPSSECURE_DOCBLOCK_RETURN", "The function %s returns a value and must include @returns in its docblock.");
define("_CS_XOOPSSECURE_DOCBLOCK_PARAM", "The function %s parameters must match those in its docblock @param.");
define("_CS_XOOPSSECURE_DOCBLOCK_THROW", "The function %s throws an exception and must include @throws in its docblock.");
define("_CS_XOOPSSECURE_UNARY_OPERATOR", "Unary operators (++ or --) must not be used inside a control statement");
define("_CS_XOOPSSECURE_INSIDE_ASSIGNMENT", "Assigments (=) must not be used inside a control statement.");
define("_CS_XOOPSSECURE_FUNCTION_LENGTH_THROW", "The %s function body length is too long. [%s/%s]");
define("_CS_XOOPSSECURE_EMPTY_BLOCK", "Empty %s block.");
define("_CS_XOOPSSECURE_EMPTY_STATEMENT", "Avoid empty statements (;;).");
define("_CS_XOOPSSECURE_HEREDOC", "Heredoc syntax must not be used.");
define("_CS_XOOPSSECURE_MAX_PARAMETERS", "The function %s's number of parameters (%s) must not exceed %s.");
define("_CS_XOOPSSECURE_NEED_BRACES", "The statement %s must contain its code within a {} block.");
define("_CS_XOOPSSECURE_SWITCH_DEFAULT", "The switch statement must have a default case.");
define("_CS_XOOPSSECURE_SWITCH_DEFAULT_ORDER", "The default case of a switch statement must be located after all other cases.");
define("_CS_XOOPSSECURE_SWITCH_CASE_NEED_BREAK", "The case statement must contain a break.");
define("_CS_XOOPSSECURE_UNUSED_PRIVATE_FUNCTION", "Unused private function: %s.");
define("_CS_XOOPSSECURE_UNUSED_VARIABLE", "Undeclared or unused variable: %s.");
define("_CS_XOOPSSECURE_UNUSED_FUNCTION_PARAMETER", "The function %s parameter %s is not used.");
define("_CS_XOOPSSECURE_ONE_CLASS_PER_FILE", "File %s must not have multiple class declarations.");
define("_CS_XOOPSSECURE_ONE_INTERFACE_PER_FILE", "File %s must not have multiple interface declarations.");
define("_CS_XOOPSSECURE_FUNCTION_INSIDE_LOOP", "%s function must not be used inside a loop.");
define("_CS_XOOPSSECURE_UNUSED_CODE", "Function %s has unused code after %s.");
define("_CS_XOOPSSECURE_DEPRECATED_FUNCTION", "%s is deprecated since PHP %s. %s must be used instead.");
define("_CS_XOOPSSECURE_ALIASED_FUNCTION", "%s is an alias, consider replacing with %s.");
define("_CS_XOOPSSECURE_REPLACED", "Consider replacing %s with %s.");
define("_CS_XOOPSSECURE_USE_STRICT_COMPARE", "Consider using a strict comparison operator instead of %s.");
define("_CS_XOOPSSECURE_EMPTY_FILE", "The file %s is empty.");
define("_CS_XOOPSSECURE_PHP_TAGS_START_LINE", "PHP tag should be at the beginning of the line.");
define("_CS_XOOPSSECURE_MANDATORY_HEADER", "Mandatory header not found.");
define("_CS_XOOPSSECURE_VARIABLE_NAMING_LENGTH_SHORT", "Variable %s name length is too short.");
define("_CS_XOOPSSECURE_VARIABLE_NAMING_LENGTH_LONG", "Variable %s name length is too long.");
define("_CS_XOOPSSECURE_PREFER_SINGLE_QUOTES", "Prefer single-quoted strings when you don't need string interpolation.");
define("_CS_XOOPSSECURE_PREFER_DOUBLE_QUOTES", "Prefer double-quoted strings unless you need single quotes to avoid extra backslashes for escaping.");
define("_CS_XOOPSSECURE_VARIABLE_VARIABLE", "Avoid using variable variables like %s");
define("_CS_XOOPSSECURE_THIS_IN_STATIC_FUNCTION", "\$this must not be used inside a static function");
