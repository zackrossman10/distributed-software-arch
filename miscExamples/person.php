<!DOCTYPE HTML>
<html lang="en">
<head>
</head>

<body>

<h2>Person Class and objects</h2>

<?php
class Person {
    private $fname;
    private $lname;
 
    //public function __construct($fname, $lname = '') {
    public function __construct ($fname, $lname = '') {
       
        $this->fname = $fname;
        $this->lname = $lname;
    }
 
    public function greet() {
        return 'Hello, my name is ' . $this->fname .
               (($this->lname != '') ? (' ' . $this->lname) : '') . '.';
    }
 
    public static function staticGreet($fname, $lname) {
        return 'Hello, my name is ' . $fname . ' ' . $lname . '.';
    }
}
 
$he    = new Person('John', 'Doe');
$she   = new Person('Amy', 'Smith');
$other = new Person('iAm');
 
echo $he->greet(); // "Hello, my name is John Doe."
echo '<br />';
 
echo $she->greet(); // "Hello, my name is Amy Smith."
echo '<br />';
 
echo $other->greet(); // "Hello, my name is iAm."
echo '<br />';
 
echo Person::staticGreet('Jane', 'Smith'); // "Hello, my name is Jane Smith."

class  Student extends Person {

    public function __construct($fname, $lname, $class) {
        parent::__construct($fname, $lname);
        $this->class = $class;   
    }

}

$john    = new Student('John', 'Doe', "CS135");











?>

</body>
</html>
