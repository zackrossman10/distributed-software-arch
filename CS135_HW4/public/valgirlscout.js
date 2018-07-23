//validate this field element
//take in the legend element, its corresponding input,
//and a special validate function for each type of input
function valFunction(legend, input, specialValFn, specialMessage){
  //create element to hold notifications
  let notificationElement = $("<span class = 'info '></span>");
  //insert notification element before legend
  notificationElement.insertBefore(legend);
  input.focus(function(){
    //track user progress towards correct input each keyup
    //if user doens not have acceptable answer after keyup -> yellow
    //if user does have acceptable answer after keyup -> green
    input.keyup(function(){
      if(isNonEmpty(input)){
        if(specialValFn(input)){
          input.removeClass('editing');
          input.removeClass('error');
          input.addClass("ok");
          notificationElement.html('');
        }else{
          input.addClass('editing');
        }
      }
    });
  }).focusout(function(){
    input.removeClass('editing');
    //if user doesn't have a value or a correct value after exiting input box -> red
    if(isNonEmpty(input)){
      if(!specialValFn(input)){
        input.addClass('error');
        notificationElement.html(specialMessage);
      }
    }else{
      input.addClass('error')
      notificationElement.html('Empty field');
    }
  })
}

//map the names of inputs to their respective validation function
let arraySpecialFns = {
  'name' : [isAlphabetical, 'Only alphabetical'],
  'street' : [isNonEmpty, 'Empty Field'],
  'city' : [isAlphabetical, 'Only alphabetical'],
  'state' : [isAlphabetical, 'Only alphabetical'],
  'zip' : [isZip, 'Invalid Zip'],
  'email' : [isEmail, 'Invalid email'],
  'phone' : [isPhoneNumber, 'Invalid phone'],
  'troop_name' : [isAlphabetical, 'Only alphabetical'],
  'scout_name' : [moreThan3, '< 3 chars']
}

//all the error messages if the valfunctions evaluate to false
let errorMessages = {
  isAlphabetical : 'Only alphabetical',
  isNonEmpty: 'Empty field',
  isZip: 'Invalid zip',
  isEmail : 'Invalid email',
  isPhoneNumber : 'Invalid phone'
}

$(document).ready(function (){
  //get each legend element
  $('legend').each(function(){
    let name = $(this).attr('name');
    //get each input element associated with this legend
    let input = $('input[name='+name+']');
    //get the special validation valFunction
    let specialValFn = arraySpecialFns[name][0];
    //get the special error message
    let specialMessage = arraySpecialFns[name][1];
    //validate
    valFunction(this, input, specialValFn, specialMessage);
  });
});

//make arrays of acceptable chars and numbers
let acceptableChar = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
let acceptableNum = "1234567890".split("");
let bothCharNum = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");

//make sure field is non-empty
function isNonEmpty(fieldElem){
  let val = $(fieldElem).val();
  return val !== "";
}

//check that each char of the value is alphabetical
function isAlphabetical(fieldElem){
  let val = fieldElem.val().split("");
  for(var i = 0; i<val.length; i++){
    if(!acceptableChar.includes(val[i]) && val[i] !== " "){
      return false;
    }
  }
  return true;
}

function isZip(fieldElem){
  let val = fieldElem.val().split("");
  for(var i = 0; i< val.length; i++){
    if(!acceptableNum.includes(val[i])){
      return false;
    }
  }
  return val.length === 5;
}

//validate phone number
function isPhoneNumber(fieldElem){
  let text = fieldElem.val();
  if(text.length != 10){
    return false;
  }
  for(let i = 0; i<9; i++){
    if(!acceptableNum.includes(text.charAt(i))){
      return false;
    }
  }
  return true;
}

//validate email of format "X@X.[com/edu/gov]""
function isEmail(fieldElem){
  let text = fieldElem.val();
  let atSignIndex = text.indexOf('@');
  let dotSignIndex = text.indexOf('.');
  //if text doesn't include '@' or '.' or the '@' comes before the '.', return false
  if(atSignIndex <0 || dotSignIndex <0 || atSignIndex>dotSignIndex){
    return false
  }
  //get the string of text before the @ sign
  let firstString = text.substring(0, atSignIndex);
  //get string of text in between @ sign and .
  let secondString = text.substring((atSignIndex+1), dotSignIndex);
  if(firstString === "" || secondString === ""){
    return false
  }
  //check that the strings only contain alphabetical chars
  for(let i = 0; i<firstString.length; i++){
    if(!bothCharNum.includes(firstString.charAt(i))){
      return false;
    }
  }
  for(let i = 0; i<secondString.length; i++){
    if(!bothCharNum.includes(secondString.charAt(i))){
      return false;
    }
  }
  //get the enging of the email after the '.'
  let ending = text.substring((dotSignIndex+1), text.length);
  //make sure the ending = 'com', 'edu', or 'gov'
  return (ending === 'com' || ending === 'gov' || ending ==='edu');
}

//determine if field has 3 or more chars
function moreThan3(fieldElem){
  let val = fieldElem.val().split("");
  return val.length >= 3;
}
