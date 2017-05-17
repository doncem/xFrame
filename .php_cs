<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('lib/addendum')
    ->exclude('nbproject')
    ->exclude('report')
    ->exclude('tmp')
    ->exclude('vendor')
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config('strict');

// https://github.com/FriendsOfPHP/PHP-CS-Fixer#usage
return $config
    ->setCacheFile('tmp/.php_cs.cache')
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        // core
        '@PHP70Migration' => true,
        '@PHP71Migration' => true,
        '@PSR1' => true,
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        // @Symfony overrides
//        'braces' => ['position_after_functions_and_oop_constructs' => 'same'],
        'blank_line_after_opening_tag' => false,
        'class_definition' => ['singleItemSingleLine' => true],
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'single'],
//        'no_blank_lines_after_class_opening' => false,//flipping not working
        'no_extra_consecutive_blank_lines' => [
            'break',
            'continue',
            'curly_brace_block',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'throw',
            'use',
            'use_trait'
         ],
        'no_leading_namespace_whitespace' => false,
        'normalize_index_brace' => false,
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_no_package' => false,
        'silenced_deprecation_error' => false,
        'trailing_comma_in_multiline_array' => false,
        // additionals
        'array_syntax' => ['syntax' => 'short'],
        'class_keyword_remove' => true,
        'combine_consecutive_unsets' => true,
        'general_phpdoc_annotation_remove' => ['@author'],
        'linebreak_after_opening_tag' => true,
        'mb_str_functions' => true,
        'native_function_invocation' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'pow_to_exponentiation' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'ternary_to_null_coalescing' => true
    ])
    ->setUsingCache(true)
;
