<?php

namespace App\Traits;

use Illuminate\Support\Facades\Input;

trait FilterableTrait {

    public function scopeFilterable($query) {

        if (Input::has('filter')) {

            $filterString = Input::get('filter');
            $keys = explode(',', $filterString);

            foreach ($keys as $key) {
    			$value = '';
    			$value = substr($key, strpos($key, '(') + 1, -1);
    			$key = substr($key, 0, strpos($key, '('));

                if (in_array($key, $this->filter)) {
                    $query = $query->where($key, 'LIKE', '%'.trim($value).'%');
                }
    		}

        }

        return $query;

    }

}
