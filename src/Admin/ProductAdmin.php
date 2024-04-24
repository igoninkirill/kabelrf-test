<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface  $collection): void
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $collection->clearExcept(['list', 'edit', 'delete', 'batch']);
        } elseif ($this->isGranted('ROLE_MANAGER')) {
            $collection->clearExcept(['list', 'show']);
        }
    }


    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('name', TextType::class, [
                'label' => 'Product Name'
            ])
            ->add('code', TextType::class, [
                'label' => 'Symbolic Code (URL)'
            ])
            ->add('isActive', CheckboxType::class, [
                'required' => false,
                'label' => 'Active'
            ])
            ->add('isTagged', CheckboxType::class, [
                'required' => false,
                'label' => 'Tagging'
            ])
            ->add('sortOrder', IntegerType::class, [
                'label' => 'Sorting Order'
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => 'Product Image',
                'help' => 'Select the image file.'
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name')
            ->add('code')
            ->add('isActive')
            ->add('isTagged');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('name')
            ->add('code')
            ->add('isActive')
            ->add('isTagged')
            ->add('sortOrder')
            ->add('imageName', 'imageFile', [
                'template' => 'admin/product/image.html.twig'
            ]);
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('name')
            ->add('code')
            ->add('isActive')
            ->add('isTagged')
            ->add('sortOrder')
            ->add('imageName');
    }
}
