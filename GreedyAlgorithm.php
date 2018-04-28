<?php
declare(strict_types=1);
require_once('IKnapsackProblemSolver.php');

/**
 * Class GreedyAlgorithm
 */
class GreedyAlgorithm implements IKnapsackProblemSolver
{

    /**
     * Runs algorithm.
     * @param array $itemsArray             array of items
     * @param float $knapsackWeight         knapsack's maximum weight
     * @return Knapsack                     optimized Knapsack
     */
    static public function run(array &$itemsArray, float &$knapsackWeight): Knapsack
    {
        $current_weight = 0.0;
        $current_value = 0.0;
        $current_knapsack_contents = array();
        $added_items = 0;
        $items_number = sizeof($itemsArray);

        // Sort items according to value-weight ratio ("better" items are at the beginning of the array)
        usort($itemsArray, array("GreedyAlgorithm", "compare_items"));

        // Add items to knapsack as long as weight doesn't exceed knapsack's limit
        for($i = 0; $i < $items_number; $i++) {
            if($current_weight + $itemsArray[$i]->getWeight() <= $knapsackWeight) {
                $current_weight += $itemsArray[$i]->getWeight();
                $current_value += $itemsArray[$i]->getValue();
                $current_knapsack_contents[$added_items++] = $itemsArray[$i];
            }
        }

        return new Knapsack($current_knapsack_contents, $current_weight, $current_value);
    }


    /**
     * Compare two items by their value to weight ratio.
     * @param Item $item1       first item to compare
     * @param Item $item2       second item to compare
     * @return bool             comparison result
     */
    private function compare_items(Item &$item1, Item &$item2) : bool
    {
        $first_item_ratio = (float)$item1->getValue() / $item1->getWeight();
        $second_item_ratio = (float)$item2->getValue() / $item2->getWeight();
        return $first_item_ratio < $second_item_ratio;
    }
}