<?php
class Comment{
  const MAX_COMMENT_BY_PAGE_AND_USER = 1;
  private int $id;
  private string $comment;
  private string $subject;
  private string $created_date;
  private int $stars;
  private string $username;

  public function __construct(int $id, string $comment, string $subject, string $created_date, int $stars, string $username) {
    $this->id = $id;
    $this->setComment($comment);
    $this->setSubject($subject);
    $this->setCreatedDate($created_date);
    $this->setStars($stars);
    $this->setUsername($username);
  }

  public function delete(PDO $db):?string{
    try {
      $sql = "DELETE FROM `comment` WHERE id=?;";
      $prepare = $db->prepare($sql);
      $prepare->execute([$this->id]);
      $prepare->closeCursor();
      return true;
    }catch (Exception $e){
      return $e->getMessage();
    }

    return null;
  }

  public static function getCommentsByUserAndRecipe(PDO $db, int $recipe_id, int $user_id):array|string{
    try {
      $sql = "SELECT `comment`.*, `user`.`name` AS username FROM `comment` LEFT JOIN `user` ON `comment`.`user_id`=`user`.`id` WHERE `recipe_id`=? AND `user_id`=?";
      $prepare = $db->prepare($sql);
      $prepare->execute([$recipe_id, $user_id]);
      $results = $prepare->fetchAll();
      $comments = [];
      foreach($results as $result){
        array_push($comments, new Comment($result["id"], $result["comment"], $result['subject'], $result["created_date"], $result["stars"], $result["username"]));
      }
      $prepare->closeCursor();
      return $comments;
    }catch (Exception $e){
      return $e->getMessage();
    }
  }

  /** stars must be from 1 -> 10 */
  public static function insertComment(PDO $db, int $recipe_id, int $user_id, string $comment, string $subject, int $stars){
    if (sizeof(self::getCommentsByUserAndRecipe($db, $recipe_id, $user_id))>=self::MAX_COMMENT_BY_PAGE_AND_USER){
      return "trop de commentaires insérés par vous sur cette page";
    }
    try {
      $sql = "INSERT INTO `comment`(`recipe_id`, `user_id`, `comment`, `subject`, `stars`) VALUES(?,?,?,?,?);";
      $prepare = $db->prepare($sql);
      $prepare->execute([$recipe_id, $user_id, $comment, $subject, $stars]);
      $prepare->closeCursor();
      return self::getCommentsByUserAndRecipe($db, $recipe_id, $user_id);
    }catch (Exception $e){
      return $e->getMessage();
    }

    return null;
  }

  // getters
  public function getId():int{
    return $this->id;
  }
  public function getComment():string{
    return $this->comment;
  }
  public function getSubject():string{
    return $this->subject;
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
    $this->comment = trim(htmlspecialchars(strip_tags($comment)),ENT_QUOTES);
    return $this;
  }
  public function setSubject(string $subject):self{
    $this->subject = trim(htmlspecialchars(strip_tags($subject)),ENT_QUOTES);
    return $this;
  }
  public function setCreatedDate(string $created_date):self{
    $this->created_date = $created_date;
    return $this;
  }
  public function setStars(int $stars):self{
    $this->stars = $stars;
    return $this;
  }
  public function setUsername(string $username):self{
    $this->username = $username;
    return $this;
  }
}