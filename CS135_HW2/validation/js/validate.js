//validate a certain field
var validateField = function(fieldElem, infoMessage, validateFn) {

  //declare the notification element
  let notificationElement = $("<span class ='extra'></span>");
  //insert the notification element
  notificationElement.insertAfter(fieldElem);
  //while field is being edited, show the notification element
  //and add class "info"
  $(fieldElem).focusin( function (){
    notificationElement.html(infoMessage);
    notificationElement.show();
    notificationElement.addClass('info');
  });
  //while field is not being edited
  $(fieldElem).focusout( function (){
    //remove the class info
    notificationElement.removeClass('info');
    //if input is empty, hide notification
    if(fieldElem.val() === ""){
      notificationElement.hide();
    }else{
      //if form validates, text = ok, class = ok, and visible
      if(validateFn(fieldElem)){
        notificationElement.html('OK');
        notificationElement.removeClass('error');
        notificationElement.addClass('ok');
        notificationElement.show();
      }else{
        notificationElement.html('Error');
        notificationElement.addClass('error');
        notificationElement.show();
      }
    }
  });
};

$(document).ready(function() {
	// TODO: Use validateField to validate form fields on the page.
  let firstnameInput = $("input[name*='firstname']");
  validateField(firstnameInput, "Must include only alphabetical characters", valFirstName);
  let lastnameInput = $("input[name*='lastname']");
  validateField(lastnameInput, "Must include only alphabetical characters", valFirstName);
  let usernameInput = $("input[name*='username']");
  validateField(usernameInput, "Must include only alphabetical characters", valFirstName);
  let passwordInput = $("input[name*='password']");
  validateField(passwordInput, "Must be at least 8 chars long and contain 1 number", valPassword);
  let emailInput = $("input[name*='email']");
  validateField(emailInput, "Must be of the format [a-z]@[a-z].[com, edu, gov]", valEmail);
  let phoneInput = $("input[name*='phone']");
  validateField(phoneInput, "Must be of the format XXX XXX XXXX", valPhone);
  let radioInput = $("input[name*='radColleges']");
  validateField(radioInput, "At least 1 option must be selected", val1CollegePressed);
  let attractionsInput = $("input[name*='chkAttr']");
  validateField(attractionsInput, "At least 2 options must be selected", val2AttractionsPressed);
});

//make arrays of acceptable chars and numbers
let acceptableChar = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
let acceptableNum = "1234567890".split("");

//make sure 2 checkboxes are selected
function val2AttractionsPressed(fieldElem){
  let count = 0;
  for(let i = 0; i<fieldElem.length; i++){
    if(fieldElem[i].checked){
      count++
    }
  }
  return count >= 2;
}

//makes sure 1 radio button was pressed
function val1CollegePressed(fieldElem) {
  for(let i = 0; i<fieldElem.length; i++){
    if(fieldElem[i].checked){
      return true;
    }
  }
  return false;
}

//make sure phone is of correct format
function valPhone(fieldElem){
  let text = fieldElem.val();
  if(text.length !== 12) return false;
  //find first 3 numbers
  let indexFirstSpace = text.indexOf(' ');
  let secondHalf = text.substring(indexFirstSpace+1, text.length);
  let indexSecondSpace = secondHalf.indexOf(' ');
  if(indexFirstSpace !== 3 || indexSecondSpace !==3) return false;

  let second3 = secondHalf.substring(0,indexSecondSpace);
  let first3 = text.substring(0,indexFirstSpace);
  //check that the chars in first3 and second3 are numbers
  for(let i = 0; i<3; i++){
    if(!acceptableNum.includes(first3.charAt(i)) && !acceptableNum.includes(second3.charAt(i))){
      return false;
    }
  }

  let last4 = secondHalf.substring(indexSecondSpace+1, secondHalf.length);
  //check that the chars in last4 are numbers
  for(let i = 0; i<4; i++){
    if(!acceptableNum.includes(last4.charAt(i))){
      return false;
    }
  }
  return true;

}

//verify that email field is of the format "[a-z]@[a-z].[com, edu, gov]"
function valEmail(fieldElem){
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
    if(!acceptableChar.includes(firstString.charAt(i))){
      return false;
    }
  }
  for(let i = 0; i<secondString.length; i++){
    if(!acceptableChar.includes(secondString.charAt(i))){
      return false;
    }
  }
  //get the enging of the email after the '.'
  let ending = text.substring((dotSignIndex+1), text.length);
  //make sure the ending = 'com', 'edu', or 'gov'
  return (ending === 'com' || ending === 'gov' || ending ==='edu');
}

//verify that firstname field only contains characters
function valFirstName(fieldElem) {
  //create an array of characters for the text
  let text = fieldElem.val().split("");
  //see if each char is in the alphabet
  for(let i = 0; i<text.length; i++){
    if(!acceptableChar.includes(text[i])){
      return false;
    }
  }
  return true;
}

//verify that password is 8 chars long and includes a number
function valPassword(fieldElem){
  let text = fieldElem.val().split("");
  //whether password contains a number
  let existsNum = false;
  for(let i = 0; i<text.length; i++){
    if(acceptableNum.includes(text[i])){
      existsNum = true;
      break;
    }
  }
  return existsNum & (text.length>7);
}
