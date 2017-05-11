<?php

namespace AppBundle\Form;

use AppBundle\Transformers\CategoryTransformer;
use AppBundle\Transformers\TagTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProductType extends AbstractType
{
    private $manager;

    /**
     * ProductType constructor.
     * @param $manager
     */
    public function __construct($manager)
    {
        $this->manager = $manager;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class)
            ->add('price', MoneyType::class)
            ->add('tags', TextType::class)
            ->add('category', TextType::class)
            ->add('image_form', FileType::class, [
                    'data_class' => null,
                    'required' => false
                ]
            )
        ;

        $builder->get('tags')->addModelTransformer(new TagTransformer());
        $builder->get('category')->addModelTransformer(
            new CategoryTransformer($this->manager)
        );

//        $builder->get('tags')->addModelTransformer(new TagTransformer());
//        if (!empty($this->manager)) {
//            $builder->get('category')->addModelTransformer(
//                new CategoryTransformer($this->manager)
//            );
//        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
