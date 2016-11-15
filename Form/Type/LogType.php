<?php

namespace Troopers\MetricsBundle\Form\Type;

use Monolog\Logger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troopers\MetricsBundle\DataTransformer\JsonToArrayTransformer;

class LogType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new JsonToArrayTransformer();
        $builder
            ->add('message', TextType::class, [
                'label' => 'Message:',
            ])
            ->add('level', ChoiceType::class, [
                'label'   => 'Level:',
                'choices' => [
                    'Debug'     => Logger::DEBUG,
                    'Info'      => Logger::INFO,
                    'Notice'    => Logger::NOTICE,
                    'Warning'   => Logger::WARNING,
                    'Error'     => Logger::ERROR,
                    'Critical'  => Logger::CRITICAL,
                    'Alert'     => Logger::ALERT,
                    'Emergency' => Logger::EMERGENCY,
                ],
                'choices_as_values' => true,
            ])
            ->add('datetime', DateTimeType::class, [
                'label'       => 'Date (dd/MM/yyyy):',
                'required'    => false,
                'date_format' => 'dd/MM/yyyy',
                'date_widget' => 'single_text',
                'html5'       => true,
            ])
            ->add($builder->create('context')->addModelTransformer($transformer));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Troopers\MetricsBundle\Model\Log',
            'translation_domain' => 'metrics',
        ]);
    }
}
