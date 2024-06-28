<?php
class SubRecipe{
  private int $id;
  private string $title;
  private ?string $img_url;
  private int $preparation_time;
  private array $instructions;

  public function __construct(int $id, string $title, ?string $img_url, int $preparation_time, array $instructions) {
    $this->id = $id;
    $this->setTitle($title);
    if ($img_url)$this->setImgUrl($img_url);
    $this->setPreparationTime($preparation_time);
    $this->setInstructions($instructions);
  }

  public function update(PDO $db):string|bool{
    try {
      $sql = "
        UPDATE `sub_recipe`
        SET
          `title`=?,
          `image_url`=?,
          `preparation_time`=?
        WHERE
          `id`=?
      ";
      $prepare = $db->prepare($sql);
      $prepare->execute([$this->title, $this->img_url, $this->preparation_time, $this->id]);
      $prepare->closeCursor();
      return true;
    }catch (Exception $e){
      return $e->getMessage();
    }
  }

  /**
   * @return true if success , string if error
   */
  public function delete(PDO $db):string|bool{
    try {
      $sql = "DELETE FROM `sub_recipe` WHERE id=?;";
      $prepare = $db->prepare($sql);
      $prepare->execute([$this->id]);
      $prepare->closeCursor();
      return true;
    }catch (Exception $e){
      return $e->getMessage();
    }
  }

  // getters
  public function getId():int{
    return $this->id;
  }
  public function gettitle():string{
    return nl2br($this->title);
  }
  public function getImgUrl():?string{
    return $this->img_url;
  }
  public function getPreparationTime():int{
    return $this->preparation_time;
  }
  public function getInstructions():array{
    return $this->instructions;
  }

  // setters
  public function setTitle(string $title):self{
    $this->title = $title;
    return $this;
  }
  public function setImgUrl(string $img_url):self{
    $this->img_url = $img_url;
    return $this;
  }
  public function setPreparationTime(int $preparation_time):self{
    $this->preparation_time = $preparation_time;
    return $this;
  }
  public function setInstructions(array $instructions):self{
    $this->instructions = $instructions;
    return $this;
  }
}
