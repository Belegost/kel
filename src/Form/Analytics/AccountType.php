<?php

namespace App\Form\Analytics;

use App\Entity\Integrity\Analytics\Account;
use App\Form\Data\AnalyticsData;
use App\Form\Data\SignUpData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class AccountType
 * @package App\Form\Analytics
 */
class AccountType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('APIkey', Type\TextType::class, ['label' => 'API key'])
            ->add('APIsecret', Type\TextType::class, ['label' => 'API secret'])
            ->add('submit', Type\SubmitType::class, ['label' => 'Add account']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnalyticsData::class,
        ]);
    }
}
