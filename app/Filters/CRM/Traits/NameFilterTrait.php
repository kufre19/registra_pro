<?php


namespace App\Filters\CRM\Traits;

trait NameFilterTrait
{
//    public function searchByName($value = null)
    public function name($value = null)
    {
        $this->builder
            ->where('name', 'LIKE', "%{$value}%");
    }

}
