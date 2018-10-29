<?php

namespace AppBundle\Form;

use AppBundle\Entity\Exp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpType extends CarrierType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
        ->add('location', TextType::class, ['label' => 'Location'])
        ->add('dateFrom', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date début'
        ])
            ->add('dateTo', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date fin'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Exp::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_exp';
    }


}
