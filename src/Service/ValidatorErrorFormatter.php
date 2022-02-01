<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidatorErrorFormatter {
	public static function format(ConstraintViolationListInterface $errors) {
		$result = [];

		foreach ($errors as $key => $error) {
			$result[] = $error->getPropertyPath(). ": ". $error->getMessage();
		}

		return $result;
	}
}
