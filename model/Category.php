<?php
class Category{
  private int $id;
  private string $name;

  public function __construct(int $id, string $name) {
    $this->id = $id;
    $this->setName($name);
  }

  // getters
  public function getId():int{
    return $this->id;
  }
  public function getName():string{
    return $this->name;
  }

  // setters
  public function setName(string $name):self{
    $this->name = $name;
    return $this;
  }
}
