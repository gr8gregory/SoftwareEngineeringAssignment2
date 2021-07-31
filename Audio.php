<?php
    class audio{
        public function playAudioUp(){
            echo "<script> 
				var audio = new Audio('./audio/up.mp3');
 				audio.play();
 				</script>";
        }
        public function playAudioDown(){
            echo "<script> 
				var audio = new Audio('./audio/down.mp3');
 				audio.play();
 				</script>";
        }
    }
?>