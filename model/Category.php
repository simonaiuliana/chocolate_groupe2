<?php
class Category{
  private int $id;
  private string $name;

  public function __construct(int $id, string $name) {
    $this->id = $id;
    $this->setName($name);
  }

  public function delete(PDO $db):?string{
    try {
      $sql = "DELETE FROM `category` WHERE id=?;";
      $prepare = $db->prepare($sql);
      $prepare->execute([$this->id]);
      $prepare->closeCursor();
      return true;
    }catch (Exception $e){
      return $e->getMessage();
    }

    return null;
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
