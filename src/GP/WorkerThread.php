<?php

namespace App\GP;


use App\Entity\Student;
use App\Entity\Topic;


/**
 *This is WorkerThread class.
 * User: MaHome
 * Date: 3/10/2019
 * Time: 1:50 PM
 */

class WorkerThread {
    //Class properties goes here.....
    private $topics;
    private $students;
    /**
     * @var double
     */
    private $fitness;
    private $interestBoard;
    /**
     * @var int
     */
    private $generationCount;
    /**
     * @var DNA
     */
    private $bestFit;
    /**
     * @var int
     */
    private $loop;
    /**
     * WorkerThread constructor.
     * @param Topic[] $topics
     * @param Student[] $students
     */
    public function __construct(array $topics, array $students){
        $this->fitness = 0;
        $this->topics = $topics;
        $this->students =$students;
        $this->generationCount = 0;
        $this->loop = 0;
    }

    /**
     * This method execute the algorithm;
     * @return DNA
     */
    public function run(){
        $this->interestBoard = [];
        $topicIDs = [];
        foreach ($this->topics as $topic){
            $priority = [];
            foreach ($this->students as $student){
                if($student->getFirstInterest() == $topic->getDomain()->getName()){
                    $priority[$student->getStudentId()] = 1;
                }else if($student->getSecondInterest() == $topic->getDomain()->getName()){
                    $priority[$student->getStudentId()] = 0.5;
                }else if($student->getSecondInterest() == $topic->getDomain()->getName()) {
                    $priority[$student->getStudentId()] = 0.25;
                }else{
                    $priority[$student->getStudentId()] = 0;
                }
            }
            $this->interestBoard[$topic->getTopicId()] = $priority;
            $topicIDs[] = $topic->getTopicId();
        }
        $population = new Population();
        while($population->size() < 20){
            $population->add($this->initialDNA($this->students,$topicIDs));
        }
        $generation = new Generation($population);
        //compute optimize solution.
        while($this->fitness < 90){
            $this->loop++;
            //compute fitness..
            $generation->fitness(function(DNA $dna){
                $fitness = 0;
                foreach($dna->getGenes() as $studentID => $topicID){
                    $fitness += $this->interestBoard[$topicID][$studentID];
                }
                if($this->fitness < (($fitness/$dna->size()) * 100)){
                    $this->fitness = ($fitness/$dna->size()) * 100;
                    $this->bestFit = $dna;
                }
                return $fitness;
            });
            //perform Natural Selection
            $generation->naturalSelection();

            //Compute mutation..
            $generation = $generation->next($this->interestBoard, function (DNA $dna){
                $studentID = array_keys($dna->getGenes());
                $studentID = $studentID[array_rand($studentID)];
                $topicID = array_values($dna->getGenes());
                $value = array_values(array_diff(array_keys($this->interestBoard),$topicID));
                $value = $value[array_rand($value)];
                $dna->set($studentID,$value);
                $this->generationCount++;
                return $dna;
            });
        }

        //Optimized allocation
        return $this->bestFit;
    }

    /**
     * @param Student[] $students
     * @param array $topicIDs
     * @return DNA
     */
    private  function initialDNA(array $students, array $topicIDs){
        $dna = new DNA();
        $topics = $topicIDs;
        foreach($students as $student){
            $dna->set($student->getStudentId(),$topics[array_rand($topics)]);
            $topics = array_diff($topicIDs,array_values($dna->getGenes()));
        }
        return $dna;
    }

    /**
     * @return float
     */
    public function getFitness(): float
    {
        return $this->fitness;
    }

    /**
     * @return int
     */
    public function getGenerationCount(): int
    {
        return $this->generationCount;
    }

    /**
     * @return int
     */
    public function getLoop(): int
    {
        return $this->loop;
    }

}