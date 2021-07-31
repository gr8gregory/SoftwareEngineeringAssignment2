<?php

class node{
    private $status = 0;
    private $floor;     
    private $nodeID;    

    public function __construct( int $node, int $flr){ // 3 objects will be made for each floor button
        
        $this->$floor = $flr;
        $this->$nodeID = $node;
    }
    
}
?>