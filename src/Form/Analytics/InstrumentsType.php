<?php

namespace App\Form\Analytics;

use App\Entity\Integrity\Analytics\Exchange;
use App\Entity\Integrity\Analytics\Instrument;
use App\Form\Data\InstrumentsData;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class InstrumentsType
 * @package App\Form\Analytics
 */
class InstrumentsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('instruments', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'name',
                'property_path' => 'instruments',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('i')
                        ->where('i.exchange = :exchange')
                        ->andWhere('i.name in (:assets)')
                        ->setParameters([
                            'exchange' => Exchange::BITFINEX,
                            'assets' => $options['assets'],
                        ]);
                },
                'attr' => [
//                    'class' => 'checked',
//                    'checked'   => 'checked',
                    'data'   => true,
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'choice_value' => 'id',

            ])
            ->add('submit', Type\SubmitType::class, ['label' => 'Add Account']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'assets' => null,
            'data_class' => InstrumentsData::class,
        ]);
    }
}
