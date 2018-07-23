<?php

 $states = Array("Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida",'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana',
'Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan',
'Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon', 'Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah', 'Vermont','Virginia',
'Washington','West Virginia','Wisconsin','Wyoming');

//suggest values for the state field
  $val = $_REQUEST['suggest'];
  $length = strlen($val);
  $suggestions = Array();
  foreach($states as $state){
    if($length<strlen($state)){
      $substring = substr($state, 0, $length);
      if(strtolower($substring) == strtolower($val)){
        array_push($suggestions, $state);
      }if(sizeof($suggestions) > 1){
        break;
      }
    }
  }
  foreach($suggestions as $state){
    echo $state.', ';
  }
?>
