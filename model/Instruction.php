<?php
class Instruction{
  private int $id;
  private string $text;
  private ?string $img_url;

  public function __construct(int $id, string $text, ?string $img_url) {
    $this->setId($id);
    $this->setText($text);
    if ($img_url)$this->setImgUrl($img_url);
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
  public function setId(int $id):self{
    $this->id = $id;
    return $this;
  }
  public function setText(string $text):self{
    $this->text = $text;
    return $this;
  }
  public function setImgUrl(string $img_url):self{
    $this->img_url = $img_url;
    return $this;
  }
}
