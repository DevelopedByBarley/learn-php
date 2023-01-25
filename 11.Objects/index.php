<?php
  class Product {

    


/**

    private $name;
    private $price;   //private -> kivülről nem elérhetőek, public -> kivülről elérhetőek!

    public function __construct($name, $price) {
      $this->name = $name;
      $this->price = $price;
    }
 */
    //Egyszerüsitett verzió php 8 óta;

    public function __construct(private $name, private $price)
    {}


    public function setName($name) {
      $this->name = $name;
    }

    public function giveDiscount() {
      $this->price = $this->price * 0.9;
    }

    public function getDescription() {
      return "A termék neve {$this->name} ára pedig: {$this->price}";
    }
  }

  $product = new Product("Iphone 12", 345000);
  $product->giveDiscount();
  $product->setName("Iphone 12 (akciós)");
  echo $product->getDescription();



  $product_2 = new Product("Galaxy watch 3", 99000);
