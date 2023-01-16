<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'symlab:create-user',
    description: 'Symlab installation',
    hidden: false
)]
class Installation extends Command
{
    protected static $defaultDescription = 'Creates a new user.';

    public function __construct(bool $requirePassword = false)
    {
        $this->requirePassword = $requirePassword;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to create a user...')
        ;$this
        // configure an argument
        ->addArgument('email', InputArgument::OPTIONAL, 'The email of the user.');
        $this
            // ...
            ->addArgument('password', $this->requirePassword ? InputArgument::OPTIONAL : InputArgument::OPTIONAL, 'User password')
        ;
        
        // ...
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $io = new SymfonyStyle($input, $output);

        $io->title('Installation of SymBoard');
        $io->progressStart(100);
        $io->newLine(2);
        $io->section('Create Database and Database Tables');
        
        //Database
        $command = $this->getApplication()->find('doctrine:database:create');
        $result = $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('doctrine:schema:update');
        $result = $command->run(new ArrayInput(['--force'  => true, '--complete' => true]), $output);
        
        $io->progressAdvance(10);
        $io->newLine(2);

        //User
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        
        $io->section('Create Admin Account');
        
        if(!$email) {
            $email = $io->ask('Enter your email?');
        }
        
        $io->progressAdvance(10);
        $io->newLine(2);

        if(!$password) {
            $password = $io->askHidden('Enter your password!', function ($password) {
                if (empty($password)) {
                    throw new \RuntimeException('Password cannot be empty.');
                }

                return $password;
            });
        }
        
        $io->progressAdvance(10);
        $io->newLine(2);
        

        
        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a user.');
        //$output->writeln('Username: '.$input->getArgument('username'));
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}