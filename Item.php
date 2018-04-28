<?php
declare(strict_types=1);
/**
 * Class Item
 */
class Item
{
    private $id;
    private $weight;
    private $value;


    /**
     * Item constructor.
     * @param int $id               item's id
     * @param float $weight         item's weight
     * @param float $value          item's value
     */
    public function __construct(int $id, float $weight, float $value)
    {
        $this->id = $id;
        $this->weight = $weight;
        $this->value = $value;
    }


    /**
     * Display item's information
     * @return string   information about item
     */
    public function __toString()
    {
        return "Item(ID: " . $this->id . ", Weight: " . $this->weight . ", Value: " . $this->value . ")\n";
    }


    /**
     * Returns item's id
     * @return int              item's id
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * Sets item's id
     * @param int $id           new id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * Returns item's weight
     * @return float            item's weight
     */
    public function getWeight(): float
    {
        return $this->weight;
    }


    /**
     * Sets item's weight
     * @param float $weight     new weight
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }


    /**
     * Returns item's value
     * @return float            item's value
     */
    public function getValue(): float
    {
        return $this->value;
    }


    /**
     * Sets item's value
     * @param float $value      new value
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

}