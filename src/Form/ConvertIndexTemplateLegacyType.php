<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConvertIndexTemplateLegacyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fields = [];

        $fields[] = 'confirm';

        foreach ($fields as $field) {
            switch ($field) {
                case 'confirm':
                    $builder->add('confirm', CheckboxType::class, [
                        'label' => 'confirm',
                        'required' => true,
                        'constraints' => [
                            new NotBlank(),
                        ],
                    ]);
                    break;
            }
        }
    }

    public function getBlockPrefix()
    {
        return 'data';
    }
}
