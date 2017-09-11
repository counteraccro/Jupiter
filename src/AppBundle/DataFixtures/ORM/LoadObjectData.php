<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\Object;

class LoadObjectData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {
	/**
	 *
	 * @var ContainerInterface
	 */
	private $container;
	
	
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
	
	public function load(ObjectManager $manager)
	{
		$objectArray = $this->container->getParameter('Object');
		
		foreach( $objectArray as $name => $obj )
		{
			$object = new Object();
			
			foreach( $obj as $key => $value )
			{
				if($key == 'setCategoryObject')
				{
					$object->{$key}($this->getReference($value));
				}
				else 
				{
					$object->{$key}($value);
				}
			}
			
			$manager->persist($object);
			$this->addReference($name, $object);
		}
		$manager->flush();
	}
	
	public function getOrder()
	{
		return 2;
	}
}