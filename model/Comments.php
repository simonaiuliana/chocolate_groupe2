<?php
class Comment{
  private int $id;
  private string $comment;
  private string $created_date;
  private int $stars;
  private string $username;

  public function __construct(int $id, string $comment, string $created_date, int $stars, string $username) {
    $this->id = $id;
    $this->setComment($comment);
    $this->setCreatedDate($created_date);
    $this->setStars($stars);
    $this->setUsername($username);
  }

  // getters
  public function getId():int{
    return $this->id;
  }
  public function getComment():string{
    return $this->comment;
  }
  public function getCreatedDate():string{
    return $this->created_date;
  }
  public function getStars():int{
    return $this->stars;
  }
  public function getUsername():int{
    return $this->username;
  }

  // setters
  public function setComment(string $comment):self{
    $this->comment = $comment;
    return $this;
  }
  public function setCreatedDate(string $created_date):self{
    $this->$created_date = $created_date;
    return $this;
  }
  public function setStars(int $stars):self{
    $this->$stars = $stars;
    return $this;
  }
  public function setUsername(string $username):self{
    $this->$username = $username;
    return $this;
  }
}