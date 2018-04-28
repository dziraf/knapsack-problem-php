<?php

/**
 * Interface IKnapsackProblemSolver
 */
interface IKnapsackProblemSolver
{
    /**
     * Runs algorithm.
     * @param array $itemsArray             array of items
     * @param float $knapsackWeight         knapsack's maximum weight
     * @return Knapsack                     optimized Knapsack
     */
    static public function run(array &$itemsArray, float &$knapsackWeight) : Knapsack;
}