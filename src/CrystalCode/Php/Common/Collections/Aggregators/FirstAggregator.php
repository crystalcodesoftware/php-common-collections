<?php

namespace CrystalCode\Php\Common\Collections\Aggregators;

use CrystalCode\Php\Common\Collections\AggregatorBase;
use CrystalCode\Php\Common\Collections\AggregatorFactory;
use CrystalCode\Php\Common\Collections\AggregatorFactoryInterface;
use CrystalCode\Php\Common\Collections\Collection;
use CrystalCode\Php\Common\Collections\CollectionInterface;

final class FirstAggregator extends AggregatorBase
{

    /**
     * 
     * @return AggregatorFactoryInterface
     */
    public static function getAggregatorFactory(): AggregatorFactoryInterface
    {
        return new AggregatorFactory('first', function (callable $predicate = null) {
            return new FirstAggregator($predicate);
        });
    }

    /**
     * 
     * @param callable $predicate
     */
    public function __construct(callable $predicate = null)
    {
        if ($predicate === null) {
            $predicate = Collection::getDefaultPredicate();
        }

        $applicator = function ($result, $value, $key, CollectionInterface $collection, &$break) use ($predicate) {
            if ((bool) call_user_func_array($predicate, [$value, $key, $collection, &$break])) {
                $break = true;
                return $value;
            }

            return $result;
        };

        parent::__construct($applicator, null);
    }

}
