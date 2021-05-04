<?php

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PhpCsFixer' => true,
        '@PSR1' => true,
        '@PSR2' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_unsets' => true,
        'concat_space' => [ 'spacing' => 'one'],
        'explicit_string_variable' => false,
        'fopen_flags' => ['b_mode' => true],
        'heredoc_to_nowdoc' => true,
        'increment_style' => false,
        'list_syntax' => ['syntax' => 'short'],
        'multiline_whitespace_before_semicolons' => false,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'php_unit_strict' => true,
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_no_alias_tag' => false,
        'phpdoc_order' => true,
        'phpdoc_types_order' => ['sort_algorithm' => 'alpha', 'null_adjustment' => 'always_last'],
        'semicolon_after_instruction' => true,
        'single_line_throw' => false,
        'strict_comparison' => true,
        'strict_param' => true,
        'yoda_style' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('ext')
            ->in(__DIR__)
    )
;
