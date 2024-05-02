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
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class UserAdmin extends AbstractAdmin
{

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        if ($this->isGranted('ROLE_ADMIN')) {
            $collection->clearExcept(['list', 'edit', 'delete', 'batch', 'create', 'export', 'show']);
        } elseif ($this->isGranted('ROLE_MANAGER')) {
            $collection->clearExcept(['list', 'show']);
        }
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('username', TextType::class, [
                'label' => 'username'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => $this->isCurrentRoute('create')
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'enabled'
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Manager' => 'ROLE_MANAGER',
                    'Admin' => 'ROLE_ADMIN',
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
            ->add('username')
            ->add('roles', 'array')
            ->add('enabled')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('email')
            ->add('username')
            ->add('enabled');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('email')
            ->add('username')
            ->add('roles', 'array')
            ->add('enabled');
    }

    public function prePersist(object $object): void
    {
        $this->handleUserPassword($object);
    }

    public function preUpdate(object $object): void
    {
        $this->handleUserPassword($object);
    }

    private function handleUserPassword($user): void
    {
        if ($user->getPassword()) {
            $user->setPassword(
                password_hash($user->getPassword(), PASSWORD_BCRYPT)
            );
        } else {
            $uow = $this->getModelManager()->getEntityManager($user)->getUnitOfWork();
            $originalData = $uow->getOriginalEntityData($user);
            $user->setPassword($originalData['password']);
        }
    }
}
