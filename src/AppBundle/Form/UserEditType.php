<?php

namespace AppBundle\Form;

use AppBundle\Entity\Exp;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $transporteur = $options['transporteur'];


        $builder->add('client', EntityType::class, array(
            'class' => 'AppBundle:Client',
            'choice_label' => 'name',
            'placeholder' => 'Clients...',
            'multiple' => false,
             'attr' => ['readonly' => true , 'disabled' =>'disabled']

        ))

        ;
        $builder->add('code',TextType::class,

          ['attr' => ['readonly' => true , 'disabled' =>'disabled']]

        );

        switch ($transporteur) {
            case 1 :
                $builder->add('carrier', ColiType::class,
                    ['attr' => ['readonly' => true , 'disabled' =>'disabled']]
                );
                break;

            case 2:
                $builder->add('carrier', ImxType::class,
                    ['attr' => ['readonly' => true , 'disabled' =>'disabled']]
                )
                ;
                break;

            case 3:
                $builder->add('carrier', ExpType::class
                ,  ['attr' => ['readonly' => true , 'disabled' =>'disabled']]
                );
                break;
        }


        // just a regular save button to persist the changes
        $builder->remove('save');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'transporteur' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return UserType::class;
    }


}
