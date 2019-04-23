<?php

if (! defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(static function ($packageKey) {
    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
    $dispatcher = $objectManager->get(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);

    $dispatcher->connect(\Ssch\Typo3AliceFixtures\Persistence\DatabasePurgerFactory::class, \Ssch\Typo3AliceFixtures\Events::AFTER_PURGE_EVENT, \Ssch\Typo3AliceFixtures\Aspect\InitializeInstallationAfterDatabasePurge::class, 'handle');
}, 'typo3_alice_fixtures');
