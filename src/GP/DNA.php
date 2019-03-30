<?php

namespace App\GP;


/**
 *This is DNA class.
 * User: MaHome
 * Date: 2/27/2019
 * Time: 1:59 PM
 */
class DNA{
    //Class properties goes here.....
    /**
     * @var array
     */
    private $genes;

    /**
     * This is the class constructor.
     * DNA constructor.
     * @param array|null $genes
     */
    public function __construct(array $genes = []){
        $this->genes = $genes;
    }

    /**
     * @return array
     */
    public function getGenes(): array
    {
        return $this->genes;
    }

    /**
     * @param array $genes
     */
    public function setGenes(array $genes): void
    {
        $this->genes = $genes;
    }

    /**
     * This method  change the gene value of a given index
     * @param int $index
     * @param $value
     */
    public function set(int $index, $value){
        $this->genes[$index] = $value;
    }

    /**
     * This method add a new gene information into the genes
     * @param $value
     */
    public function add($value){
        $this->genes[] = $value;
    }

    /**
     * This method returns the gene in a given index
     * @param int $index
     * @return mixed
     */
    public function get(int $index){
        return $this->genes[$index];
    }

    /**
     * This returns the number of genes present.
     * @return int
     */
    public function size():int {
        return count($this->genes);
    }

    /**
     * @param DNA $dna
     * @return bool
     */
    public function equal(DNA $dna): bool {
        return $this->getGenes() === $dna->getGenes()?true:false;
    }

}