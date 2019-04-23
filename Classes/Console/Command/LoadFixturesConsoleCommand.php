<?php
declare(strict_types = 1);

namespace Ssch\Typo3AliceFixtures\Console\Command;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Ssch\Typo3AliceFixtures\Loader\LoaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class LoadFixturesConsoleCommand extends Command
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    public function __construct(string $name = null, LoaderInterface $loader = null)
    {
        if (! $loader instanceof LoaderInterface) {
            $loader = self::getObjectManager()->get(LoaderInterface::class);
        }

        $this->loader = $loader;
        parent::__construct($name);
    }

    /**
     * Configure the command by defining the name, options and arguments
     */
    public function configure(): void
    {
        $this
            ->setAliases(['fixtures:load'])
            ->setDescription('Load data fixtures to your database.')
            ->addOption(
                'extensions',
                'e',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Extensions where fixtures should be loaded.'
            )
            ->addOption(
                'append',
                null,
                InputOption::VALUE_NONE,
                'Append the data fixtures instead of deleting all data from the database first.'
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Warn the user that the database will be purged
        // Ask him to confirm his choice
        if ($input->isInteractive() && ! $input->getOption('append') && false === $this->askConfirmation(
                $input,
                $output,
                '<question>Careful, database will be purged. Do you want to continue y/N ?</question>',
                false
            )) {
            return 0;
        }

        // Ensure the _cli_ user is authenticated
        Bootstrap::initializeBackendAuthentication();

        $extensions = $input->getOption('extensions');
        $append = $input->getOption('append');

        $this->loader->load($extensions, $append);
    }

    /**
     * Prompts to the user a message to ask him a confirmation.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param ConfirmationQuestion|string $question
     * @param bool $default
     *
     * @return bool User choice
     */
    private function askConfirmation(InputInterface $input, OutputInterface $output, $question, $default): bool
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelperSet()->get('question');
        $question = new ConfirmationQuestion($question, $default);

        return (bool)$questionHelper->ask($input, $output, $question);
    }

    /**
     * @return object|ObjectManager
     */
    private static function getObjectManager(): ObjectManagerInterface
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}
