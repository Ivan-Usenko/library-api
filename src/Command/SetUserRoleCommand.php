<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:set-user-role',
    description: 'Set "ROLE_USER" role for existing user.',
)]
class SetUserRoleCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Target user email.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user) {
            $user->setRoles([]);
            $this->em->persist($user);
            $this->em->flush();
            $io->success(sprintf('User %s is now just a user!', $email));
            return Command::SUCCESS;
        }
        else {
            $io->error(sprintf('User %s doesn\'t exist!', $email));
            return Command::FAILURE;
        }
    }
}
