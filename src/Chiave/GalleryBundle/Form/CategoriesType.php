<?php

namespace Chiave\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

use Chiave\GalleryBundle\Entity\Categories;

class CategoriesType extends AbstractType
{
    protected $currentId;

    public function __construct ($currentId = 0)
    {
        $this->currentId = $currentId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentId = $this->currentId;

        $now = new \DateTime('now');
        $builder
            ->add('name')
            ->add('description')
            ->add('file', new FilesType(), array(
                  'label'   => ' ',
              ))
            ->add('parent', 'entity', array(
                    'class' => 'ChiaveGalleryBundle:Categories',
                    'query_builder' => function(EntityRepository $er) use ($currentId) {
                        return $er->createQueryBuilder('c')
                            ->where('c.id != :id')
                            // ->andWhere('c.parent IS NULL')
                            //->andWhere('c.isParent = false')
                            ->andWhere('c.hasItems = false')
                                ->setParameter('id', $currentId)
                            ->orderBy('c.name', 'ASC')
                        ;
                    },
                    'required' => false,
                )
            )
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
            'data_class' => 'Chiave\GalleryBundle\Entity\Categories'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chiave_gallerybundle_categories';
    }
}
