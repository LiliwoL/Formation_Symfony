<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Swift_Mailer;

final class TestMailCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:test-mail')

            // the short description shown while running "php bin/console list"
            ->setDescription('Sends a test mail.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command tests your email configuration...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Email Testing',
            '============',
            '',
        ]);

        $kernel = $this->getApplication()->getKernel();

        $mailer = $kernel->getContainer()->get('mailer');


        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('send@example.com')
        ->setTo('recipient@example.com')
        ->setBody(
           "Body"
        );

        $mailer->send($message);


        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');

        // outputs a message without adding a "\n" at the end of the line
        $output->write('Mail envoyé? ');
    }
}