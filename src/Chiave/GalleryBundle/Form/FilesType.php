<?php

namespace Chiave\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

use Chiave\GalleryBundle\Entity\Files;

class FilesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $now = new \DateTime('now');
        $builder
            ->add('key')
            ->add('name')
            ->add('category', 'entity', array(
                    'class' => 'ChiaveGalleryBundle:Categories',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('f')
                            ->orderBy('f.name', 'ASC');
                    },
                    'required' => false,
                )
            )
            ->add('description')
            ->add('visible')
            ->add('file')
            ->add('submit',
                'submit',
                array(
                    'label' => 'Wyślij'
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
            'data_class' => 'Chiave\GalleryBundle\Entity\Files'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chiave_gallerybundle_files';
    }
}
