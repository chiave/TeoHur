<?php

namespace Chiave\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('message', null, array(
                    'attr'    => array(
                        'cols' => 62,
                        'rows' => 8,
                    )
                )
            )
            ->add('submit',
                'submit',
                array(
                    'label' => 'Wyślij',
                    'attr'  => array(
                        'class'    => 'submit'
                    )
                )
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Chiave\ContactBundle\Entity\Contacts'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chiave_contactbundle_contacts';
    }
}
