<?php

namespace App\Form;

use App\Form\Data\ContactData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class ContactType
 * @package App\Form
 */
class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,[
                'label' => 'First Name'
            ])
            ->add('email', TextType::class)
            ->add('phone', TextType::class,[
                'attr' => [
                    'class' => 'focused'
                ]
            ])
            ->add('message', TextareaType::class)
            ->add('save', SubmitType::class,[
                'label' => 'Send Message',
                'attr' => [
                    'class' => 'btn btn_blue'
                ]
            ]);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactData::class,
            'attr'=>array('novalidate'=>'novalidate')
        ]);
    }

}