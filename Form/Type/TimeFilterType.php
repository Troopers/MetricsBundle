<?php
namespace Troopers\MetricsBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('since', ChoiceType::class, [
                'label' => 'dashboard.since.label',
                'required' => false,
                'choices' => [
                    "dashboard.since.choices.Today.label" => "today",
                    "dashboard.since.choices.This week.label" => "this week",
                    "dashboard.since.choices.This month.label" => "this month",
                    "dashboard.since.choices.This year.label" => "this year",
                    "dashboard.since.choices.Yesterday.label" => "yesterday",
                    "dashboard.since.choices.Day before yesterday.label" => "yesterday -1 day",
                    "dashboard.since.choices.Last 15 minutes.label" => "15 minutes ago",
                    "dashboard.since.choices.Last 30 minutes.label" => "30 minutes ago",
                    "dashboard.since.choices.Last 1 hour.label" => "1 hour ago",
                    "dashboard.since.choices.Last 4 hours.label" => "4 hours ago",
                    "dashboard.since.choices.Last 12 hours.label" => "12 hours ago",
                    "dashboard.since.choices.Last 24 hours.label" => "24 hours ago",
                    "dashboard.since.choices.Last 7 days.label" => "7 days ago",
                    "dashboard.since.choices.Last 30 days.label" => "30 days ago",
                    "dashboard.since.choices.Last 60 days.label" => "60 days ago",
                    "dashboard.since.choices.Last 90 days.label" => "90 days ago",
                    "dashboard.since.choices.Last 6 months.label" => "6 months ago",
                    "dashboard.since.choices.Last 1 year.label" => "1 year ago",
                    "dashboard.since.choices.Last 2 years.label" => "2 years ago",
                    "dashboard.since.choices.Last 5 years.label" => "5 years ago",
                    "dashboard.since.placeholder" => "",
                ],
                'choices_as_values' => true,
            ])
            ->add('since_custom', DateTimeType::class, [
                'label' => 'dashboard.since_custom.label',
                'required' => false,
                'format' => 'dd/MM/yyyy HH:mm',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provider' => 'datetimepicker',
                    'data-format' => 'dd/mm/YYYY H:i',
                ]
            ])
            ->add('until', DateTimeType::class, [
                'label' => 'dashboard.until.label',
                'required' => false,
                'placeholder' => 'dashboard.until.placeholder',
                'format' => 'dd/MM/yyyy HH:mm',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provider' => 'datetimepicker',
                    'data-format' => 'dd/mm/YYYY H:i',
                ]
            ])
            ->add('timezone', TimezoneType::class, [
                'label' => 'dashboard.timezone.label',
                'required' => true
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troopers\MetricsBundle\Entity\Dashboard\TimeFilter',
            'translation_domain' => 'metrics'
        ));
    }
}