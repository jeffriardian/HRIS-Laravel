<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TreeSelectCollection extends ResourceCollection
{
    public $tree = [];
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->toTree($this->collection);
        return [
            'data' => $this->tree
        ];
    }

    function toTree($collections, $pass = 0) {
        foreach ( $collections as $collection) {
            $this->tree[] = [
                'id' => $collection['id'],
                'name' => str_repeat("--", $pass).$collection['name']
            ]; 
    
            if (array_key_exists('children', $collection)){
                $this->toTree($collection['children'], $pass+1);   
            }
        }
    }
}
