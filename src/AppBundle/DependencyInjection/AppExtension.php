<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Extension for the bundle winzouCacheExtension
 * @author winzou
 */
class AppExtension extends Extension
{
	/**
	 * @see Symfony\Component\DependencyInjection\Extension.ExtensionInterface::load()
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);
		
		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader->load('services.yml');
		//$loader->load('parameters.yml');
		
		$fixture_loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/DataFixtures'));
		$fixture_loader->load('CategoryObjects.yml');
		$fixture_loader->load('Objects.yml');
		
		$data_loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/data'));
		$data_loader->load('first_name.yml');
		$data_loader->load('logs.yml');
		$data_loader->load('random_actions_conditions.yml');
	}
}