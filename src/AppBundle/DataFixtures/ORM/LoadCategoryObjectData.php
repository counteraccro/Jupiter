<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\CategoryObject;

class LoadCategoryObjectData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {
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
		$categoryObjectArray = $this->container->getParameter('CategoryObject');
		
		foreach( $categoryObjectArray as $name => $object )
		{
			$categoryObjec = new CategoryObject();
			
			foreach( $object as $key => $value )
			{
				$categoryObjec->{$key}($value);
			}
			
			$manager->persist($categoryObjec);
			$this->addReference($name, $categoryObjec);
		}
		$manager->flush();
	}
	
	public function getOrder()
	{
		return 1;
	}
}