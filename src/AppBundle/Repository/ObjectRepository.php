<?php

namespace AppBundle\Repository;

/**
 * ObjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObjectRepository extends \Doctrine\ORM\EntityRepository
{
	public function getRandomObject()
	{
		$q = $this->createQueryBuilder('o')
		->addSelect('RAND() as HIDDEN rand')
		->orderBy('rand')
		->setMaxResults(1);
		
		return $q->getQuery()->getResult()[0];
	}
}
