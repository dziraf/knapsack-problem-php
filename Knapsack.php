<?php
declare(strict_types=1);

/**
 * Class Knapsack
 */
class Knapsack
{
    private $items;
    private $totalWeight;
    private $totalValue;

    /**
     * Knapsack constructor.
     * @param array $items              knapsack contents
     * @param float $totalWeight        knapsack's total weight
     * @param float $totalValue         knapsack's total value
     */
    public function __construct(array $items, float $totalWeight, float $totalValue)
    {
        $this->items = $items;
        $this->totalWeight = $totalWeight;
        $this->totalValue = $totalValue;
    }


    /**
     * Display knapsack's information
     * @return string   information about knapsack
     */
    public function __toString()
    {
        return "--- KNAPSACK ---\n> Value: " . $this->totalValue . "\n> Weight: " . $this->totalWeight . "\n> Contents: " . $this->knapsackContents() . "\n";
    }


    /**
     * Concatenates knapsack contents to string
     * @return string       formatted knapsack contents
     */
    private function knapsackContents() : string
    {
        $contents = "\n";
        foreach($this->items as &$item) {
            $contents .= (string)$item;
        }
        return $contents;
    }


    /**
     * Returns knapsack's contents
     * @return array        array of contents
     */
    public function getItems(): array
    {
        return $this->items;
    }


    /**
     * Sets knapsack's contents
     * @param array $items      new contents
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }


    /**
     * Returns knapsack's total weight
     * @return float        knapsack's weight
     */
    public function getTotalWeight(): float
    {
        return $this->totalWeight;
    }


    /**
     * Sets knapsack's total weight
     * @param float $totalWeight        new weight
     */
    public function setTotalWeight(float $totalWeight): void
    {
        $this->totalWeight = $totalWeight;
    }


    /**
     * Returns knapsack's contents' value
     * @return float        contents' value
     */
    public function getTotalValue(): float
    {
        return $this->totalValue;
    }


    /**
     * Sets knapsack's contents' value
     * @param float $totalValue        new value
     */
    public function setTotalValue(float $totalValue): void
    {
        $this->totalValue = $totalValue;
    }


}