<?php

class elevatorButton{
    private $status;    // constnat depending on which button this is.
    private $nodeID;    // Constant depending on which button this is. 
    private $floor;     // variable

    public function __construct(int $stat, int $node){
        $this->$status = $stat;
        $this->$nodeID = $node;
    }
    public function insertElevatorNetwork($flr){
        $floor = $flr;
        // pass $floor, $status, $nodeID to sql function.
    }

}
?>