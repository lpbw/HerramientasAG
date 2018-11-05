<?
class B {
    public function method_from_b($s) {
    	echo $s;
    }
}

class C {
    public function method_from_c($s,$d) {
    	echo $s;
		echo $d;
    }
}

class A extends B
{
  private $c;

  public function __construct()
  {
    $this->c = new C;
  }

  // fake "extends C" using magic function
  public function __call($method, $args)
  {
    $this->c->$method($args[0],$args[1]);
  }
}


$a = new A;
$a->method_from_c("def",123123);
$a->method_from_b("abc");
?>