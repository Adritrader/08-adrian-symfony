<?php


namespace App\Form;


use App\Entity\Servicio;
use App\Entity\Registra;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistraType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuario_id', HiddenType::class)
            ->add('servicio_id', EntityType::class,
                ['class' => Servicio::class,
                    'choice_label' => 'nombre',
                    'choice_value' => 'id',
                    'label' => 'Servicio',
                ]
            )
            ->add('fechaCita', DateType::class)
            ->add('horaCita', ChoiceType::class, [
                'choices'  => [
                    '09:30:00' => '09:30:00',
                    '11:00:00' => '11:00:00',
                    '12:00:00' => '12:00:00',
                    '16:30:00' => '16:30:00',
                    '18:00:00' => '18:00:00',
                    '19:00:00' => '19:00:00',
                ],
            ])
            ->add('create', SubmitType::class, array('label' => 'Create'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Registra::class,
        ]);
    }

}