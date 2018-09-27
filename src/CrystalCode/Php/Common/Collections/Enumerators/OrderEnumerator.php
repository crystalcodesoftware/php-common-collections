<?php

namespace CrystalCode\Php\Common\Collections\Enumerators;

use CrystalCode\Php\Common\Collections\CollectionInterface;
use CrystalCode\Php\Common\Collections\EnumeratorBase;
use Iterator;

class OrderEnumerator extends EnumeratorBase
{

    /**
     *
     * @var callable
     */
    private $mapper;

    /**
     * 
     * @param callable $mapper
     */
    public function __construct(callable $mapper = null)
    {
        $this->mapper = $mapper ?: function ($value) {
            return $value;
        };
    }

    /**
     * 
     * @param CollectionInterface $collection
     * @return Iterator
     */
    public function iterate(CollectionInterface $collection)
    {
        $orderables = $collection->enumerateWith(new MapEnumerator(function ($value, $key) {
            return (object) [
                'key' => $key,
                'value' => $value,
                'index' => call_user_func($this->mapper, $value, $key),
            ];
        }))
        ->toArray();
        usort($orderables, function ($orderable1, $orderable2) {
            return $orderable1->index > $orderable2->index ? 1 : 0;
        });
        foreach ($orderables as $orderable) {
            yield $orderable->key => $orderable->value;
        }
    }

}
