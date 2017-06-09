<?php

class STB_Notices {

  function __construct($message){
    $this->message = $message;
  }

  public function display($type = 'error'){
    print "
      <div class=\"notice notice-$type\">
        <p>$this->message</p>
      </div>
    ";
  }

}
