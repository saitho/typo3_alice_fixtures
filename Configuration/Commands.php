<?php


use Ssch\Typo3AliceFixtures\Console\Command\LoadFixturesConsoleCommand;

return [
    'load:fixtures' => [
        'class' => LoadFixturesConsoleCommand::class,
        'schedulable' => false
    ]
];
