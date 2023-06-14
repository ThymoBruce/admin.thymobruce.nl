<?php

namespace App\Form;

use App\Entity\Project;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateProjectType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [])
            ->add('description', null, [
                'attr' => [
                    'class' => 'js-ClassicEditor',
                ],
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('gitRepository', TextType::class, [
                'label' => "Github repo url"
            ])
            ->add('url', TextType::class, [
                'label' => "Project url",
            ])
            ->add('submit', SubmitType::class, [

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}