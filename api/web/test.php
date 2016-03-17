<?php
/**
 * Created by PhpStorm.
 * User: huilai
 * Date: 16-1-6
 * Time: 下午4:27
 */


abstract class abClass{
    abstract function dogood();
}

class InsClass extends abClass{

    function dogood(){

    }
}


class MyClass{

    private $propertyName;

    /**
     * MyClass constructor.
     * @param $propertyName
     */
    public function __construct($propertyName)
    {
        $this->propertyName = $propertyName;


    }


}


class a{
    protected  $c ;
    public function a(){
        $this->c = 10;
    }
}

class b extends a{

    public function print_data(){
        echo $this->c;
    }
}

$b = new b();
$b->print_data();
