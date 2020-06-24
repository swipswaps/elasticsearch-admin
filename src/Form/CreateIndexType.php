<?php

namespace App\Form;

use App\Manager\CallManager;
use App\Model\CallRequestModel;
use App\Model\ElasticsearchIndexModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateIndexType extends AbstractType
{
    public function __construct(CallManager $callManager, TranslatorInterface $translator)
    {
        $this->callManager = $callManager;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fields = [];

        if (false == $options['update']) {
            $fields[] = 'name';
        }
        $fields[] = 'mappings';

        if (false == $options['update']) {
            foreach ($options['static_settings'] as $staticSetting => $defaultValue) {
                $fields[] = $staticSetting;
            }
        }

        foreach ($options['dynamic_settings'] as $dynamicSetting => $defaultValue) {
            $fields[] = $dynamicSetting;
        }

        foreach ($fields as $field) {
            switch ($field) {
                case 'name':
                    $builder->add('name', TextType::class, [
                        'label' => 'name',
                        'required' => true,
                        'constraints' => [
                            new NotBlank(),
                        ],
                    ]);
                    break;
                case 'settings':
                    $builder->add('settings', TextareaType::class, [
                        'label' => 'settings',
                        'required' => false,
                        'constraints' => [
                            new Json(),
                        ],
                        'attr' => [
                            'data-break-after' => 'yes',
                        ],
                    ]);
                    break;
                case 'mappings':
                    $builder->add('mappings', TextareaType::class, [
                        'label' => 'mappings',
                        'required' => false,
                        'constraints' => [
                            new Json(),
                        ],
                        'attr' => [
                            'data-break-after' => 'yes',
                        ],
                    ]);
                    break;
                default:
                    $builder->add(str_replace('.', '_', $field), TextType::class, [
                        'property_path' => 'settings['.$field.']',
                        'label' => $field,
                        'required' => false,
                        'translation_domain' => false,
                    ]);
                    break;
            }
        }

        if (false == $options['update']) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
                $form = $event->getForm();

                if ($form->has('name')) {
                    if ($form->get('name')->getData()) {
                        $callRequest = new CallRequestModel();
                        $callRequest->setMethod('HEAD');
                        $callRequest->setPath('/'.$form->get('name')->getData());
                        $callResponse = $this->callManager->call($callRequest);

                        if (Response::HTTP_OK == $callResponse->getCode()) {
                            $form->get('name')->addError(new FormError(
                                $this->translator->trans('name_already_used')
                            ));
                        }
                    }
                }
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ElasticsearchIndexModel::class,
            'static_settings' => [],
            'dynamic_settings' => [],
            'update' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'data';
    }
}
