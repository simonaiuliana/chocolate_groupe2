<?php

require_once("Instruction.php");
require_once("Ingredient.php");
require_once("Comment.php");
require_once("Category.php");

class Recipe{
  private int $id;
  private string $name;
  private int $nb_people;
  private int $preparation_time;
  private int $cooking_time;
  private ?string $img_url;
  // array of type Instruction
  private array $instructions;
  // array of type Ingredient
  private array $ingredients;
  // array of type Category
  private array $categories;
  // array of type Comments
  private array $comments;

  public function __construct(int $id, string $name, int $nb_people, int $preparation_time, int $cooking_time, ?string $img_url, array $instructions, array $ingredients, array $categories, array $comments) {
    $this->setId($id);
    $this->setName($name);
    $this->setNbPeople($nb_people);
    $this->setPreparationTime($preparation_time);
    $this->setCookingTime($cooking_time);
    if ($img_url)$this->setImgUrl($img_url);
    $this->setInstructions($instructions);
    $this->setIngredients($ingredients);
    $this->setCategories($categories);
    $this->setComments($comments);
  }

  public static function getRecipeById(PDO $db, int $id):self|string{
    try {
      $sql = "
        SELECT * FROM `recipe` r
        LEFT JOIN (
          -- ingredients
          SELECT
            GROUP_CONCAT(ing.id SEPARATOR '|||') AS ingredients_ids,
            GROUP_CONCAT(inu.unit SEPARATOR '|||') AS ingredients_units,
            GROUP_CONCAT(ing.ingredient SEPARATOR '|||') AS ingredients,
            GROUP_CONCAT(ing.image_url SEPARATOR '|||') AS ingredients_images,
            GROUP_CONCAT(inr.quantity SEPARATOR '|||') AS ingredients_quantities,
            inr.recipe_id AS inr_recipe_id
          FROM `ingredient_recipe` inr
          LEFT JOIN `ingredient_unity` inu ON inu.id=inr.ingredient_unity_id
          LEFT JOIN `ingredient` ing ON ing.id=inr.ingredient_id
          GROUP BY inr.recipe_id
        ) ing ON ing.inr_recipe_id=r.id
        LEFT JOIN (
          -- categories
          SELECT
            GROUP_CONCAT(cat.id SEPARATOR '|||') AS categories_ids,
            GROUP_CONCAT(cat.category SEPARATOR '|||') AS categories,
            rc.recipe_id AS rc_recipe_id
          FROM `category` cat
          LEFT JOIN `recipe_category` rc ON rc.category_id=cat.id
          GROUP BY rc.recipe_id
        ) cat ON cat.rc_recipe_id=r.id
        LEFT JOIN (
          -- comments
          SELECT
            GROUP_CONCAT(com.id SEPARATOR '|||') AS comments_ids,
            GROUP_CONCAT(com.comment SEPARATOR '|||') AS comments,
            GROUP_CONCAT(com.created_date SEPARATOR '|||') AS comments_created_dates,
            GROUP_CONCAT(com.stars SEPARATOR '|||') AS comments_stars,
            GROUP_CONCAT(u.name SEPARATOR '|||') AS comments_username,
            com.recipe_id AS com_recipe_id
          FROM `comment` com
          LEFT JOIN `user` u ON com.user_id=u.id
          GROUP BY com.recipe_id
        ) com ON com.com_recipe_id=r.id
        LEFT JOIN (
          -- instructions
          SELECT
            GROUP_CONCAT(ins.id SEPARATOR '|||') AS instructions_ids,
            GROUP_CONCAT(ins.text_content SEPARATOR '|||') AS instructions,
            GROUP_CONCAT(ins.image_url SEPARATOR '|||') AS instructions_image_url,
            ins.recipe_id
          FROM `instruction` ins
          GROUP BY ins.recipe_id
          ORDER BY ins.instruction_number
        ) ins ON ins.recipe_id=r.id;
        WHERE id=$id
      ";
      $query = $db->query($sql);
      $result = $query->fetch(PDO::FETCH_ASSOC);
      $query->closeCursor();
      /*get returned values*/
      /* recipe */
      $recipe_id = $result["id"];
      $recipe_name = $result["name"];
      $recipe_nb_people = $result["nb_people"];
      $recipe_img_url = $result["image_url"];
      $recipe_preparation_time = $result["preparation_time"];
      $recipe_cooking_time = $result["cooking_time"];
      /* ingredients */
      $ingredients_ids = explode("|||", $result["ingredients_ids"]);
      $ingredients_units = explode("|||", $result["ingredients_units"]);
      $ingredients_names = explode("|||", $result["ingredients"]);
      $ingredients_images = explode("|||", $result["ingredients_images"]);
      $ingredients_quantities = explode("|||", $result["ingredients_quantities"]);
      /* categories */
      $categories_ids = explode("|||", $result["categories_ids"]);
      $categories_names = explode("|||", $result["categories"]);
      /* comments */
      $comments_ids = explode("|||", $result["comments_ids"]);
      $comments_texts = explode("|||", $result["comments"]);
      $comments_created_dates = explode("|||", $result["comments_created_dates"]);
      $comments_stars = explode("|||", $result["comments_stars"]);
      $comments_usernames = explode("|||", $result["comments_username"]);
      /* instructions */
      $instructions_ids = explode("|||", $result["instructions_ids"]);
      $instructions_texts = explode("|||", $result["instructions"]);
      $instructions_imgs = explode("|||", $result["instructions_image_url"]);

      /*put the returned values in objects*/
      /*ingredients*/
      $recipe_ingredients = [];
      for ($i=0;$i<sizeof($ingredients_quantities);$i++){
        array_push($recipe_ingredients, new Ingredient($ingredients_ids[$i], $ingredients_quantities[$i], $ingredients_units[$i], $ingredients_names[$i], $ingredients_images[$i]));
      }
      /*categories*/
      $recipe_categories = [];
      for ($i=0;$i<sizeof($recipe_categories);$i++){
        array_push($recipe_categories, new Category($categories_ids[$i], $categories_names[$i]));
      }
      /*comments*/
      $recipe_comments = [];
      for ($i=0;$i<sizeof($recipe_comments);$i++){
        array_push($recipe_comments, new Comment($comments_ids[$i], $comments_texts[$i], $comments_created_dates[$i], $comments_stars[$i], $comments_usernames[$i]));
      }
      /*instructions*/
      $recipe_instructions = [];
      for ($i=0;$i<sizeof($recipe_instructions);$i++){
        array_push($recipe_comments, new Instruction($instructions_ids[$i], $instructions_texts[$i], $instructions_imgs[$i]));
      }

      return new Recipe($recipe_id, $recipe_name, $recipe_nb_people, $recipe_preparation_time, $recipe_cooking_time, $recipe_img_url, $recipe_instructions, $recipe_ingredients, $recipe_categories, $recipe_comments);
      return $result["nb"];
    }catch (Exception $e){
      return $e->getMessage();
    }
  }
  public static function getRecipeByName(PDO $db, string $name):self|string{
    try {
      $sql = "SELECT `id` FROM `recipe` WHERE `name`=?";
      $prepare = $db->prepare($sql);
      $prepare->execute([$name]);
      $result = $prepare->fetch();
      $prepare->closeCursor();

      if (is_string($result))return $result;
      return self::getRecipeById($db, $result["id"]);
    }catch (Exception $e){
      return $e->getMessage();
    }
  }
  public static function create(PDO $db, string $name, int $nb_people, int $preparation_time, int $cooking_time, ?string $img_url):self|string{
    $name = trim(htmlspecialchars(strip_tags($name)),ENT_QUOTES);
    if ($img_url)$img_url = trim(htmlspecialchars(strip_tags($img_url)),ENT_QUOTES);

    try {
      $sql = "
        INSRT INTO `recipe`(`name`, `nb_people`, `preparation_time`, `cooking_time`, `img_url`)
        VALUES(?,?,?,?,?);
      ";
      $prepare = $db->prepare($sql);
      $prepare->execute([$name, $nb_people, $preparation_time, $cooking_time, $img_url]);
      $prepare->closeCursor();
      return true;
    }catch (Exception $e){
      return $e->getMessage();
    }

    return self::getRecipeByName($db, $name);
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
  // return an array of Ingredient
  public function getIngredients():array{
    return $this->ingredients;
  }
  // return an array of Category
  public function getCategories():array{
    return $this->categories;
  }
  // return an array of Comment
  public function getComments():array{
    return $this->comments;
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
  //ingredients is an array of Ingredient
  public function setIngredients(array $ingredients):self{
    //check $ingredients validity
    foreach ($ingredients as $ingredient){
      if (!$ingredient instanceof Ingredient){
        throw new Exception("ingredients must be an array of Ingredient only");
      }
    }

    $this->ingredients = $ingredients;
    return $this;
  }
  //instruction is an array of Category
  public function setCategories(array $categories):self{
    //check $categories validity
    foreach ($categories as $category){
      if (!$category instanceof Category){
        throw new Exception("categories must be an array of Category only");
      }
    }

    $this->categories = $categories;
    return $this;
  }
  //comments is an array of Comment
  public function setComments(array $comments):self{
    //check $comments validity
    foreach ($comments as $comment){
      if (!$comment instanceof Comment){
        throw new Exception("comments must be an array of Comment only");
      }
    }

    $this->comments = $comments;
    return $this;
  }
}
