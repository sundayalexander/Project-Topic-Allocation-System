<?php

namespace App\GP;


/**
 *This is Population class.
 * User: MaHome
 * Date: 2/27/2019
 * Time: 2:12 PM
 */
class Population{
    //Class properties goes here.....
    /**
     * @var array
     */
    private $dnas;

    public function __construct(array $dnas = []){
        $this->dnas = $dnas;
    }

    /**
     * @return int
     */
    public function size():int {
        return count($this->dnas);
    }

    /**
     * @param array $dnas
     */
    public function setDnas(array $dnas): void
    {
        $this->dnas = $dnas;
    }

    /**
     * @return DNA[]
     */
    public function getDnas(): array
    {
        return $this->dnas;
    }

    /**
     * @param DNA $dna
     */
    public function add(DNA $dna){
        $this->dnas[] = $dna;
    }

    /**
     * @param int $index
     * @return DNA
     */
    public function get(int $index): DNA{
        return $this->dnas[$index];
    }

}