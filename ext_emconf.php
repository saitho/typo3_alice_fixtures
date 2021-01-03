<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Alice fixtures for TYPO3',
    'description' => 'Alice fixtures for TYPO3',
    'category' => 'misc',
    'author' => 'Sebastian Schreiber',
    'author_email' => 'breakpoint@schreibersebastian.de',
    'state' => 'alpha',
    'clearCacheOnLoad' => false,
    'version' => '',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.13-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
