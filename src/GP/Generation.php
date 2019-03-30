<?php

namespace App\GP;


use Doctrine\DBAL\Types\DateTimeType;

/**
 *This is Generation class.
 * User: MaHome
 * Date: 2/27/2019
 * Time: 4:38 PM
 */
class Generation{
    //Class properties goes here.....
    /**
     * @var Population
     */
    private $population;

    /**
     * @var array
     */
    private $fitness;
    /**
     * @var array
     */
    private $matingPool;

    /**
     * Generation constructor.
     * @param Population $population
     */
    public function __construct(Population $population){
        $this->population = $population;
    }

    /**
     * This method returns the fitness of the generation.
     * @param callable $callable
     */
    public function fitness(callable $callable): void{
        $this->fitness =  array_map($callable, $this->population->getDnas());
    }

    /**
     * @return array
     */
    public function getFitness(): array {
        return $this->fitness;
    }

    /**
     * @return Population
     */
    public function getPopulation(): Population
    {
        return $this->population;
    }

    /**
     * @param Population $population
     */
    public function setPopulation(Population $population): void
    {
        $this->population = $population;
    }

    /**
     *This method performs natural selection by
     * creating a mating pool with the DNA with
     * highest fitness as the highest occurrence
     */
    public function naturalSelection(){
        $this->matingPool = [];
        $totalFitness = array_sum($this->fitness) < 1?1:array_sum($this->fitness);
        foreach( $this->fitness as $key => $value){
            $pbSize = ($value/$totalFitness) * $this->population->size(); //This is the probability size.
            for($i = 0; $i < round($pbSize); $i++){
                $this->matingPool[] = $this->population->get($key);
            }
        }
    }

    /**
     *This returns an array of the mating pool
     */
    public function getMatingPool(): array {
        return $this->matingPool;
    }

    /**
     * @param DNA $parent1
     * @param DNA $parent2
     * @param array $scoreBoard
     * @return DNA
     */
    public function crossover(DNA $parent1, DNA $parent2, array $scoreBoard): DNA {
        $child = new DNA();
        //swap
        foreach($parent1->getGenes() as  $studentID => $topicID){
            if(($scoreBoard[$topicID][$studentID] < $scoreBoard[$parent2->get($studentID)][$studentID]) &&
            !in_array($parent2->get($studentID),array_values($child->getGenes()))){
                $child->set($studentID,$parent2->get($studentID));
            }else{
                $newvalue = array_diff(array_values($parent1->getGenes()),array_values($child->getGenes()));
                $child->set($studentID,$newvalue[array_rand($newvalue)]);
            }
        }
        return $child;
    }

    /**
     * @param DNA $dna
     * @param callable $callable
     * @return DNA
     */
    public function mutate(DNA $dna, callable $callable): DNA{
        return $callable($dna);
    }

    /**
     * @param array $scoreBoard
     * @param callable $callback
     * @return Generation
     */
    public function next(array $scoreBoard, callable $callback): Generation{
        if(count($this->matingPool) < 1){
            return new Generation(new Population());
        }
        $population = new Population();
       while($population->size() < $this->population->size()) {
            $parent1 = $this->matingPool[array_rand($this->matingPool)];
            $parent2 = $this->matingPool[array_rand($this->matingPool)];
            $child = $this->crossover($parent1, $parent2, $scoreBoard);
            $population->add($this->mutate($child, $callback));
        }
        return new Generation($population);
    }

}