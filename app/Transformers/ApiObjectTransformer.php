<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ApiObjectTransformer extends TransformerAbstract {

	public function transform($object)
	{
		$publics = $object->publicAttributes();
		$publics = array_map(function($v){
			if (is_numeric($v)) { return $v; }
		    return $v ?: '';
		}, $publics);

		return $publics + [
			'id' => $object->_id,
			'updatedAt' => $object->updated_at->timestamp,
			'createdAt' => $object->created_at->timestamp
		];
	}

}
