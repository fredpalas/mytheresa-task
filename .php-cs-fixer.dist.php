<?php

$header = <<<'EOF'
This file is part of PHP CS Fixer.

(c) Fabien Potencier <fabien@symfony.com>
    Dariusz Rumiński <dariusz.ruminski@gmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

$finder = PhpCsFixer\Finder::create()
    ->exclude(['tests/Fixtures', 'docker', 'vendor'])
    ->in(__DIR__)
    ->append([__DIR__ . '/php-cs-fixer']);

$config = new PhpCsFixer\Config();
$config->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
        'concat_space' => ['spacing' => 'one'],
        'no_extra_blank_lines' => [
            'tokens' => [
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'switch',
                'throw',
            ],
        ],
        'phpdoc_no_alias_tag' => [
            'replacements' => [
                'property-read' => 'property-read',
                'property-write' => 'property',
                'type' => 'var',
                'link' => 'see',
            ],
        ],
        'class_definition' => [
            'space_before_parenthesis' => true,
            'multi_line_extends_each_single_line' => true,
        ],
    ])
    ->setFinder($finder);

return $config;
