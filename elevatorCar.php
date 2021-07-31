<?php

class elevatorCar{
    private $status;
    private $nodeID;
    private $floor;

    public function __construct(int $stat, int $node, int $flr){
        $this->$status = $stat;
        $this->$nodeID = $node;
        $this->$floor = $flr;
    }
    public function getFloor(){
        
    }
    public function getMoveFloor(){
        
    }
}

?>