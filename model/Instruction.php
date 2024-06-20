<?php
class Instruction{
  private int $id;
  private string $text;
  private ?string $img_url;

  public function __construct(int $id, string $text, ?string $img_url) {
    $this->id = $id;
    $this->setText($text);
    if ($img_url)$this->setImgUrl($img_url);
  }

  public function update(PDO $db):self|string{
    try {
      $sql = "
        UPDATE `instruction`
        SET
          `text_content`=?,
          `image_url`=?
        WHERE
          `id`=?
      ";
      $prepare = $db->prepare($sql);
      $prepare->execute([$this->text, $this->img_url, $this->id]);
      $prepare->closeCursor();
      return true;
    }catch (Exception $e){
      return $e->getMessage();
    }

    return $this;
  }

  public function delete(PDO $db):?string{
    try {
      $sql = "DELETE FROM `instruction` WHERE id=?;";
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
  public function getText():string{
    return nl2br($this->text);
  }
  public function getImgUrl():?string{
    return $this->img_url;
  }

  // setters
  public function setText(string $text):self{
    $this->text = $text;
    return $this;
  }
  public function setImgUrl(string $img_url):self{
    $this->img_url = $img_url;
    return $this;
  }
}
