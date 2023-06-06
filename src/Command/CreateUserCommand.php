<?php

namespace App\Command;

use App\Entity\Roles;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create:user',
    description: 'Create new user command',
)]
class CreateUserCommand extends Command {

	private EntityManagerInterface $entityManager;
	private UserPasswordHasherInterface $passwordHasher;

	public function __construct(
		EntityManagerInterface $entityManager,
		UserPasswordHasherInterface $passwordHasher
	) {
		$this->entityManager = $entityManager;
		$this->passwordHasher = $passwordHasher;
		parent::__construct();
	}
	
    protected function configure(): void {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'username')
			->addArgument('email', InputArgument::REQUIRED, 'email')
			->addArgument('password', InputArgument::REQUIRED, 'user password');
    }

    protected function execute(
		InputInterface $input, 
		OutputInterface $output
	): int {
		try {
			$io = new SymfonyStyle($input, $output);
			$username = $input->getArgument('username');
			$email = $input->getArgument('email');
			$password = $input->getArgument('password');

			if($username) {
				$io->note("You passed 'username': $username");
			}

			if($email) {
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$io->error('Invalid email');
					return Command::FAILURE; 
				}

				$io->note("You passed 'email': $email");
			}

			if($password) {
				$io->note("You passed 'password': $password");
			}

			if(!empty($this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]))) {
				$io->error('Such a user is already registered');
				return Command::FAILURE; 
			}

			$user = new User();
			$user->setUsername($username);
			$user->setMail($email);
			$user->setPassword(
				$this->passwordHasher->hashPassword(
					$user,
					$password
				)
			);
			$user->addRole(
				$this->entityManager->getRepository(Roles::class)->find(1)
			);

			$this->entityManager->persist($user);
			$this->entityManager->flush();

			$io->success("New user: $username created success!");
			
			return Command::SUCCESS;
		} catch (\Throwable $th) {
			//throw $th;
			$io->error($th->getMessage());
			return Command::FAILURE;
		}
    }
}
