let currentPlayer = 'red';
//track all red squares
let redArray = [];
//track all blue squares
let blueArray = [];
//represent the Grid
let grid = ['one', 'two','three','four','five','six','seven','eight','nine'];

//make a move
function play(position) {
  let square = document.getElementById(position);
  //check if move is legal
  if(isLegal(position)){
    //change background color to match current player
    square.style.backgroundColor = currentPlayer;
    //switch players and add the square to the correct array
    changeTurns(currentPlayer, position);
    if(hasWinner()){
      //show the button and statement that prints the loser
      document.getElementById('win-info').style.display = 'block';

      document.getElementById('print').innerHTML = currentPlayer.toUpperCase() +  " LOSES";
    }
  }
}

//reset all squares in grid to white,
//empty redArray and blueArray
function reset() {
  redArray = [];
  blueArray = [];
  let target;
  for(let i = 0; i<grid.length; i++){
    target = document.getElementById(grid[i]);
    target.style.backgroundColor = 'white';
  }
  document.getElementById('win-info').style.display = 'none';

  //remove play again and text
}

//change current player to the opponent of player
function changeTurns(player, position){
  if(player === 'red'){
    //add the position to the red array
    if(redArray.length === 3){
      let overflow = overflowArray(redArray, position);
      let changeToWhite = document.getElementById(overflow);
      changeToWhite.style.backgroundColor = 'white';
    }else{
      redArray.push(position);
    }
    currentPlayer = 'blue';
    let change = document.getElementById('player');
    change.innerHTML = ("Blue Player's Turn");

  }else{
    //add the position to the blue array
    if(blueArray.length === 3){
      //get the position of the square to change back to white
      let overflow = overflowArray(blueArray, position);
      //get the element to change back to white
      let changeToWhite = document.getElementById(overflow);
      //change the background color of the element ot white
      changeToWhite.style.backgroundColor = 'white';
    }else{
      blueArray.push(position);
    }
    currentPlayer = 'red';
    let change = document.getElementById('player');
    change.innerHTML = ("Red Player's Turn");
  }
}

//shift elements of array down towards 0
//return array[0]
function overflowArray(array, position){
  let overflow = array[0];
  array[0] = array[1];
  array[1] = array[2];
  array[2] = position;
  return overflow;
}

//check if move is legal by seeing if the position is in either array
function isLegal(position){
  return !((blueArray.includes(position)) || redArray.includes(position)) & !hasWinner();
}

//check if board has winner
function hasWinner() {
    var redString = redArray.toString();
    var blueString = blueArray.toString();
    console.log(blueString);
    console.log(redString);
    return blueString.includes('one') && blueString.includes('two') && blueString.includes('three')
        || blueString.includes('four') && blueString.includes('five') && blueString.includes('six')
        || blueString.includes('seven') && blueString.includes('eight') && blueString.includes('nine')
        || blueString.includes('one') && blueString.includes('four') && blueString.includes('seven')
        || blueString.includes('two') && blueString.includes('five') && blueString.includes('eight')
        || blueString.includes('three') && blueString.includes('six') && blueString.includes('nine')
        || blueString.includes('one') && blueString.includes('five') && blueString.includes('nine')
        || blueString.includes('three') && blueString.includes('five') && blueString.includes('seven')
        || redString.includes('one') && redString.includes('two') && redString.includes('three')
        || redString.includes('four') && redString.includes('five') && redString.includes('six')
        || redString.includes('seven') && redString.includes('eight') && redString.includes('seven')
        || redString.includes('one') && redString.includes('four') && redString.includes('seven')
        || redString.includes('two') && redString.includes('five') && redString.includes('eight')
        || redString.includes('three') && redString.includes('six') && redString.includes('nine')
        || redString.includes('one') && redString.includes('five') && redString.includes('nine')
        || redString.includes('three') && redString.includes('five') && redString.includes('seven');
	}
