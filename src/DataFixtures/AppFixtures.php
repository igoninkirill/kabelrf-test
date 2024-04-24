<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Создание администратора
        $admin = new User();
        $admin->setFirstName('admin');
        $admin->setLastName('admin');
        $admin->setPatronymic('admin');
        $admin->setEmail('admin@admin.ru');
        $admin->setIsActive(true);
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, 'password'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Создание менеджера
        $managerUser = new User();
        $managerUser->setFirstName('manager');
        $managerUser->setLastName('manager');
        $managerUser->setPatronymic('manager');
        $managerUser->setEmail('manager@manager.ru');
        $managerUser->setIsActive(true);
        $managerUser->setPassword($this->passwordEncoder->encodePassword($managerUser, 'password'));
        $managerUser->setRoles(['ROLE_MANAGER']);
        $manager->persist($managerUser);

        $manager->flush();
    }
}
