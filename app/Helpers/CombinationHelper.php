<?php

namespace App\Helpers;

class CombinationHelper
{
    private static function lessPeopleCount($targetSum, $capacities)
    {
        $combinations = [];
        $doubles = [];
        $triples = [];

        foreach ($capacities as $capacity) {
            if ($targetSum <= $capacity) {
                $combinations[] = [$capacity];
                continue;
            }
            $doubles[] = $capacity;
        }

        foreach ($doubles as $double) {
            if ($targetSum <= ($double * 2)) {
                $combinations[] = [$double, $double];
                continue;
            }
            $triples[] = $double;
        }

        foreach ($triples as $triple) {
            if ($targetSum <= ($triple * 3)) {
                $combinations[] = [$triple, $triple, $triple];
            }
        }

        if ($targetSum <= array_sum($doubles)) {
            $combinations[] = $doubles;
        }

        return $combinations;
    }

    private static function biggerPeopleCount($targetSum, $sortedCapacities)
    {
        $combinations = [];

        $maxCapacityDouble = $sortedCapacities[0] * 2;

        if ($targetSum > $maxCapacityDouble) {
            $count = count($sortedCapacities) - 1;
            for ($i = 1; $i <= $count; $i++) {
                if ($targetSum <= ($maxCapacityDouble + $sortedCapacities[$i])) {
                    $combinations[] = [$sortedCapacities[0], $sortedCapacities[0], $sortedCapacities[$i]] ;
                }
            }
        }

        return $combinations;
    }

    private static function morePeopleCount($targetSum, $capacities, $maxTables)
    {
        $combinations = [];
        $sortedCapacities = $capacities; // Hacer una copia
        rsort($sortedCapacities);

        foreach ($sortedCapacities as $index => $capacity) {
            for ($i = 1; $i <= $maxTables; $i++) {
                $result = $capacity * $i; // Multiplicación de capacity por el contador

                if ($result >= $targetSum) {
                    $combinations[] = array_fill(0, $i, $capacity); // Almacenar el resultado en combination
                    break; // Salir del bucle cuando se cumple la condición
                }
            }

            // Verificar si hay un siguiente elemento
            $tmp = $maxTables - 1;
            for ($j = 1; $j <= $tmp; $j++) { // Iterar sobre el siguiente y el siguiente siguiente
                if ($index + $j >= count($sortedCapacities)) {
                    continue; // Saltar si no hay un siguiente elemento
                }

                $nextCapacity = $sortedCapacities[$index + $j]; // Obtener el siguiente elemento
                $sum = $capacity + $nextCapacity;

                if ($targetSum <= $sum) {
                    $combinations[] = [$capacity, $nextCapacity];
                    continue;
                }

                if ($targetSum <= ($sum + $nextCapacity)) {
                    $combinations[] = [$capacity, $nextCapacity, $nextCapacity];
                }
            }
        }

        $combinations = array_merge($combinations, self::biggerPeopleCount($targetSum, $sortedCapacities));

        return $combinations;
    }

    public static function getCombinations($targetSum, $capacities, $maxTables)
    {

        $combinations = [];

        $combinations = $targetSum <= $capacities[count($capacities) - 1]
            ? self::lessPeopleCount($targetSum, $capacities)
            : self::morePeopleCount($targetSum, $capacities, $maxTables);

        usort($combinations, function ($a, $b) {
            // Ordenar por suma de los elementos
            $sumA = array_sum($a);
            $sumB = array_sum($b);
            if ($sumA === $sumB) {
                // Si son iguales, ordenar por la cantidad de elementos
                return count($a) - count($b);
            }
            return $sumA - $sumB; // Ordenar por suma
        });

        return $combinations;
    }
}
