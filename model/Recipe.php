<?php

require_once("Instruction.php");
require_once("Ingredient.php");
require_once("Comment.php");

class Recipe{
  private int $id;
  private string $name;
  private int $nb_people;
  private int $preparation_time;
  private int $cooking_time;
  private ?string $img_url;
  // array of type Instruction
  private array $instructions;
  // array of type string
  private array $ingredients;

  public function __construct(int $id, string $name, int $nb_people, int $preparation_time, int $cooking_time, ?string $img_url, array $instructions, array $ingredients) {
    $this->setId($id);
    $this->setName($name);
    $this->setNbPeople($nb_people);
    $this->setPreparationTime($preparation_time);
    $this->setCookingTime($cooking_time);
    if ($img_url)$this->setImgUrl($img_url);
    $this->setInstructions($instructions);
    $this->setIngredients($ingredients);
  }

  public static function getRecipeById(PDO $db, int $id):self{
    try {
      $sql = "
      SELECT r.*,
          ing.unit AS units, ing.ingredient as ingredients, ing.image_url as ingredients_images, ing.quantity as quantities,
          cat.category,
          com.comment, com.created_date, com.stars, com.username,
          ins.text_content AS instructions, ins.image_url AS instructions_img, ins.number AS instructions_number
      FROM `recipe` r
      LEFT JOIN ( SELECT GROUP_CONCAT(inu.unit SEPARATOR '|||') AS unit, GROUP_CONCAT(ing.ingredient SEPARATOR '|||') AS ingredient, GROUP_CONCAT(ing.image_url SEPARATOR '|||') AS image_url, GROUP_CONCAT(inr.quantity SEPARATOR '|||') AS quantity, inr.recipe_id
            FROM `ingredient_recipe` inr
            LEFT JOIN `ingredient_unity` inu ON inu.id=inr.ingredient_unity_id
            LEFT JOIN `ingredient` ing ON ing.id=inr.ingredient_id
            GROUP BY inr.recipe_id
            ) ing ON ing.recipe_id=r.id
      LEFT JOIN ( SELECT GROUP_CONCAT(cat.category SEPARATOR '|||') AS category, rc.recipe_id
            FROM `category` cat
            LEFT JOIN `recipe_category` rc ON rc.category_id=cat.id
            GROUP BY rc.recipe_id
            ) cat ON cat.recipe_id=r.id
      LEFT JOIN ( SELECT GROUP_CONCAT(com.comment SEPARATOR '|||') AS comment, GROUP_CONCAT(com.created_date SEPARATOR '|||') AS created_date, GROUP_CONCAT(com.stars SEPARATOR '|||') AS stars, GROUP_CONCAT(u.name SEPARATOR '|||') AS username, com.recipe_id
            FROM `comment` com
            LEFT JOIN `user` u ON com.user_id=u.id
            GROUP BY com.recipe_id
            ) com ON com.recipe_id=r.id
      LEFT JOIN ( SELECT GROUP_CONCAT(ins.text_content SEPARATOR '|||') AS text_content, GROUP_CONCAT(ins.image_url SEPARATOR '|||') AS image_url, GROUP_CONCAT(ins.instruction_number SEPARATOR '|||') AS number, ins.recipe_id
            FROM `instruction` ins
            GROUP BY ins.recipe_id
            ) ins ON ins.recipe_id=r.id;
      ";
      $query = $db->query($sql);
      $result = $query->fetch(PDO::FETCH_ASSOC);
      $query->closeCursor();
      /*get returned values*/
      $units = explode("|||", $result["units"]);
      $ingredients = explode("|||", $result["ingredients"]);
      $ingredients_images = explode("|||", $result["ingredients_images"]);
      $ingredients_quantities = explode("|||", $result["quantities"]);
      $categories = explode("|||", $result["category"]);
      $comments = explode("|||", $result["comment"]);
      $comment_created_dates = explode("|||", $result["created_date"]);
      $comment_stars = explode("|||", $result["stars"]);
      $comment_usernames = explode("|||", $result["username"]);
      $instructions = explode("|||", $result["instructions"]);
      $instructions_img = explode("|||", $result["instructions_img"]);
      $instructions_number = explode("|||", $result["instructions_number"]);

      /*put the returned values in objects*/
      /*ingredients*/
      $recipe_ingredients = [];
      for ($i=0;$i<sizeof($ingredients_quantities);$i++){
        array_push($recipe_ingredients, new Ingredient(0, $ingredients_quantities[$i], $units[$i], $ingredients[$i], $ingredients_images[$i]));
      }
      /*comments*/
      $recipe_comments = [];
      for ($i=0;$i<sizeof($comments);$i++){
        array_push($recipe_comments, new Comment(0, $comments[$i], $comment_created_dates[$i], $comment_stars[$i], $comment_usernames[$i]));
      }
      /*instructions*/
      $recipe_instructions = [];
      for ($i=0;$i<sizeof($instructions);$i++){
        array_push($recipe_comments, new Instruction(0, $instructions[$i], $instructions_img[$i], $instructions_number[$i]));
      }

      return $result["nb"];
    }catch (Exception $e){
      return $e->getMessage();
    }
  }
  public static function getRecipeByName(string $name):self{
  }
  public static function insert(string $name, ?string $img_url, array $instructions, array $ingredients):self{
  }

  public function update():self{
    return $this;
  }
  public function delete():void{
  }

  // getters
  public function getId():int{
    return $this->id;
  }
  public function getName():string{
    return $this->name;
  }
  public function getNbPeople():int{
    return $this->nb_people;
  }
  public function getPreparationTime():int{
    return $this->preparation_time;
  }
  public function getCookingTime():int{
    return $this->cooking_time;
  }
  public function getImgUrl():?string{
    return $this->img_url;
  }
  // return an array of Instruction
  public function getInstructions():array{
    return $this->instructions;
  }
  // return an array of string
  public function getIngredients():array{
    return $this->ingredients;
  }

  //setters
  public function setId(int $id):self{
    $this->id = $id;
    return $this;
  }
  public function setName(string $name):self{
    $this->name = $name;
    return $this;
  }
  public function setNbPeople(int $nb_people):self{
    $this->nb_people = $nb_people;
    return $this;
  }
  public function setPreparationTime(int $preparation_time):self{
    $this->preparation_time = $preparation_time;
    return $this;
  }
  public function setCookingTime(int $cooking_time):self{
    $this->cooking_time = $cooking_time;
    return $this;
  }
  public function setImgUrl(string $img_url):self{
    $this->img_url = $img_url;
    return $this;
  }
  //instruction is an array of Instruction
  public function setInstructions(array $instructions):self{
    //check $instructions validity
    foreach ($instructions as $instruction){
      if (!$instruction instanceof Instruction){
        throw new Exception("instructions must be an array of Instruction only");
      }
    }

    $this->instructions = $instructions;
    return $this;
  }
  //instruction is an array of string
  public function setIngredients(array $ingredients):self{
    //check $ingredients validity
    foreach ($ingredients as $ingredient){
      if (!is_string($ingredient)){
        throw new Exception("ingredients must be an array of string only");
      }
    }

    $this->ingredients = $ingredients;
    return $this;
  }
}