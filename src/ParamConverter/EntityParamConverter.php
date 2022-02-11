<?php

namespace App\ParamConverter;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Society;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

class EntityParamConverter implements ParamConverterInterface {
	public const SUPPORTED = [
		Product::class, 
		Society::class, 
		User::class
	];

	public function __construct(private ManagerRegistry $managerRegistry) {}

	public function apply(Request $request, ParamConverter $configuration): bool {
		$id = $request->get('id');
		$repository = $this->managerRegistry->getRepository($configuration->getClass());

		if (!$id) {
			return false;
		}

		$entity = $repository->find($id);

		if (is_null($entity)) {
			throw new NotFoundHttpException("No ". $configuration->getName(). " found for the provided id");
		}

		$request->attributes->set($configuration->getName(), $entity);

		return true;
	}

	public function supports(ParamConverter $configuration): bool {
		return in_array($configuration->getClass(), self::SUPPORTED);
	}
}
