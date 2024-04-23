<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('firstName', TextType::class, [
                'label' => 'First Name'
            ])
            ->add('patronymic', TextType::class, [
                'label' => 'Patronymic',
                'required' => false
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password'
            ])
            ->add('isActive', CheckboxType::class, [
                'required' => false,
                'label' => 'Active'
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN'
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Role'
            ]);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('email')
            ->add('firstName')
            ->add('lastName')
            ->add('roles', 'array')
            ->add('isActive')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('isActive');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('roles', 'array')
            ->add('isActive')
            ->add('createdAt')
            ->add('updatedAt');
    }

    public function prePersist(object $object): void
    {
        $this->manageEmbeddedImageAdmins($object);
    }

    public function preUpdate(object $object): void
    {
        $this->manageEmbeddedImageAdmins($object);
    }

    private function manageEmbeddedImageAdmins($user): void
    {
        if ($user->getPassword()) {
            $user->setPassword(
            # TODO сервис для хэширования пароля
                password_hash($user->getPassword(), PASSWORD_BCRYPT)
            );
        }
    }
}
