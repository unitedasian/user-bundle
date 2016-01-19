<?php

namespace UAM\Bundle\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to check and create bootstrap symlink into MopaBootstrapBundle
 */
class UserMigrateFromSfGuardCommand extends ContainerAwareCommand
{

  protected function configure()
    {
        $this
            ->setName('uam:user:migrate')
            ->setDescription("Migrate users from sfGuard to FOSUser")
            ->setHelp(<<<EOT
The <info>uam:user:migrate</info> command migrates all user accounts from ''sf_guard_user'' to ''fos_user''.
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $um = $this->getContainer()->get('fos_user.user_manager');

        $dbHost = $this->getContainer()->getParameter('database_host');
        $dbUser = $this->getContainer()->getParameter('database_user');
        $dbPassword = $this->getContainer()->getParameter('database_password');
        $dbName = $this->getContainer()->getParameter('database_name');
        $dbPort = $this->getContainer()->getParameter('database_port');

        $mysqli = new \mysqli($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);

        $result = $mysqli->query('SELECT * FROM sf_guard_user AS u LEFT JOIN user_profile AS p ON (u.id = p.user_id)');

        while ($user = $result->fetch_object()) {
            $username = $user->username;

            if ($um->findUserByUsername($username)) { continue; }

            $userFOS = $um->createUser();

//            $userFOS->setId($user->id);
            $userFOS->setUsername($username);
            $userFOS->setEmail($user->email);
            $userFOS->setPassword($user->password);

               $um->updateUser($userFOS);
        }
    }
}
