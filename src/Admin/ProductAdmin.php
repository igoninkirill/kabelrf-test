<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sonata\AdminBundle\Route\RouteCollection;

class ProductAdmin extends AbstractAdmin
{
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
            ->add('imageName', 'image', [
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
            ->add('image');
    }
}
