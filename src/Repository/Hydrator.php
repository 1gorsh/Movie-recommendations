<?php
declare(strict_types = 1);

namespace App\Repository;

interface Hydrator
{
    /**
     * @param array $data
     * @return mixed
     */
    public function hydrate(array $data);
}
