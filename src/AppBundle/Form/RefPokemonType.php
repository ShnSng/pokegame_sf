<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RefPokemonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('type1', EntityType::class, [
                'class' => 'AppBundle:RefElementaryType',
                'choice_label' => function ($c) {
                    return ucfirst($c);
                }
            ])
            ->add('type2', EntityType::class, [
                'class' => 'AppBundle:RefElementaryType',
                'choice_label' => function ($c) {
                    return ucfirst($c);
                },
                'required' => false
            ])
            ->add('typeCourbeNiveau', ChoiceType::class, [
                'choices' => ['R', 'M', 'P', 'L'],
                'choice_label' => function ($c, $k, $v) {
                    return $v;
                }
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RefPokemon',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_refpokemon';
    }


}
