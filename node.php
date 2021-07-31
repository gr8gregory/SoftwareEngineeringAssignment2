<?php

class node{
    private $status;
    private $floor;     
    private $nodeID;    

    public function __construct(int $stat, int $node, int $flr){ // 3 objects will be made for each floor button
        $this->$status = $stat;
        $this->$floor = $flr;
        $this->$nodeID = $node;
    }
    
}
?>