<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\Player;

class LoadPlayerData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface {
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
		$playerArray = $this->container->getParameter('Player');
		
		foreach( $playerArray as $name => $object )
		{
			$player = new Player();
			
			foreach( $object as $key => $value )
			{
				$player->{$key}($value);
			}
			
			$manager->persist($player);
			$this->addReference($name, $player);
		}
		$manager->flush();
	}
	
	public function getOrder()
	{
		return 1;
	}
}