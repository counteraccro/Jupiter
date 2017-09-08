<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlayerType extends AbstractType {
	
	/**
	 *
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name')
		->add('save',  SubmitType::class, array('label' => 'Chercher un lobby', 'attr' => array('class' => 'btn-save-player')));
	}
	
	/**
	 *
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array (
				'data_class' => 'AppBundle\Entity\Player',
				'csrf_protection' => false 
		));
	}
	
	/**
	 *
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'appbundle_player';
	}
}
