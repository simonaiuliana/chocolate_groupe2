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
            GROUP_CONCAT((CASE WHEN inu.unit THEN inu.unit ELSE 'NULL' END) SEPARATOR '|||') AS ingredients_units,
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
        ) ins ON ins.recipe_id=r.id
        WHERE id=$id;
      ";
      $query = $db->query($sql);
      $result = $query->fetch(PDO::FETCH_ASSOC);
      $query->closeCursor();
      /*get returned values*/
      /* recipe */
      if (!$result["id"])return "recette non trouvée";
      $recipe_id = $result["id"];
      $recipe_name = $result["name"];
      $recipe_nb_people = $result["nb_people"];
      $recipe_img_url = $result["image_url"];
      $recipe_preparation_time = $result["preparation_time"];
      $recipe_cooking_time = $result["cooking_time"];
      /* ingredients */
      $ingredients_ids = $result["ingredients_ids"] ? explode("|||", $result["ingredients_ids"]) : [];
      $ingredients_units = $result["ingredients_units"] ? explode("|||", $result["ingredients_units"]) : [];
      $ingredients_names = $result["ingredients"] ? explode("|||", $result["ingredients"]) : [];
      $ingredients_images = $result["ingredients_images"] ? explode("|||", $result["ingredients_images"]) : [];
      $ingredients_quantities = $result["ingredients_quantities"] ? explode("|||", $result["ingredients_quantities"]) : [];
      /* categories */
      $categories_ids = $result["categories_ids"] ? explode("|||", $result["categories_ids"]) : [];
      $categories_names = $result["categories"] ? explode("|||", $result["categories"]) : [];
      /* comments */
      $comments_ids = $result["comments_ids"] ? explode("|||", $result["comments_ids"]) : [];
      $comments_texts = $result["comments"] ? explode("|||", $result["comments"]) : [];
      $comments_created_dates = $result["comments_created_dates"] ? explode("|||", $result["comments_created_dates"]) : [];
      $comments_stars = $result["comments_stars"] ? explode("|||", $result["comments_stars"]) : [];
      $comments_usernames = $result["comments_username"] ? explode("|||", $result["comments_username"]) : [];
      /* instructions */
      $instructions_ids = $result["instructions_ids"] ? explode("|||", $result["instructions_ids"]) : [];
      $instructions_texts = $result["instructions"] ? explode("|||", $result["instructions"]) : [];
      $instructions_imgs = $result["instructions_image_url"] ? explode("|||", $result["instructions_image_url"]) : [];

      /*put the returned values in objects*/
      /*ingredients*/
      $recipe_ingredients = [];
      for ($i=0;$i<sizeof($ingredients_quantities);$i++){
        array_push($recipe_ingredients, new Ingredient($ingredients_ids[$i], $ingredients_quantities[$i], $ingredients_units[$i], $ingredients_names[$i], $ingredients_images[$i]));
      }
      /*categories*/
      $recipe_categories = [];
      for ($i=0;$i<sizeof($categories_ids);$i++){
        array_push($recipe_categories, new Category($categories_ids[$i], $categories_names[$i]));
      }
      /*comments*/
      $recipe_comments = [];
      for ($i=0;$i<sizeof($comments_ids);$i++){
        array_push($recipe_comments, new Comment($comments_ids[$i], $comments_texts[$i], $comments_created_dates[$i], $comments_stars[$i], $comments_usernames[$i]));
      }
      /*instructions*/
      $recipe_instructions = [];
      for ($i=0;$i<sizeof($instructions_ids);$i++){
        array_push($recipe_instructions, new Instruction($instructions_ids[$i], $instructions_texts[$i], $instructions_imgs[$i]));
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

  public function update(PDO $db):self{
    try {
      $sql = "
        UPDATE `recipe`
        SET
          `name`=?,
          `nb_people`=?,
          `preparation_time`=?,
          `cooking_time`=?,
          `img_url`=?
        WHERE
          `id`=?
      ";
      $prepare = $db->prepare($sql);
      $prepare->execute([$this->name, $this->nb_people, $this->preparation_time, $this->cooking_time, $this->img_url, $this->id]);
      $prepare->closeCursor();
      return true;
    }catch (Exception $e){
      return $e->getMessage();
    }

    return $this;
  }
  public function delete(PDO $db):?string{
    try {
      $sql = "DELETE FROM `recipe` WHERE id=?;";
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
  /**
   * @return array $instructions -> array of Instruction
   */
  public function getInstructions():array{
    return $this->instructions;
  }
  /**
   * @return array $ingredients -> array of Ingredient
   */
  public function getIngredients():array{
    return $this->ingredients;
  }
  /**
   * @return array $categories -> array of Category
   */
  public function getCategories():array{
    return $this->categories;
  }
  /**
   * @return array $comments -> array of Comment
   */
  public function getComments():array{
    return $this->comments;
  }

  //setters
  public function setId(int $id):self{
    $this->id = $id;
    return $this;
  }
  public function setName(string $name):self{
    $this->name = trim(htmlspecialchars(strip_tags($name)),ENT_QUOTES);
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
    $this->img_url = trim(htmlspecialchars(strip_tags($img_url)),ENT_QUOTES);
    return $this;
  }
  /**
   * @return int the average from 1 to 10 instead of 1 to 5: if returns 3(/10) -> 1.5(/5)
   */
  public function getStarAverage():int{
    if (sizeof($this->comments)===0)return 0;
    $total = 0;
    foreach($this->comments as $comment){
      $total += $comment->getStars();
    }
    $avg = ceil(($total * 2) / sizeof($this->comments));
    return $avg;
  }
  /**
   * @param array $instructions must be an array of Instruction
   */
  public function setInstructions(array $instructions):self{
    //check $instructions validity
    foreach ($instructions as $instruction){
      if (!$instruction instanceof Instruction){
        throw new Exception("les instructions peuvent uniquement être un array d'Instruction");
      }
    }

    $this->instructions = $instructions;
    return $this;
  }
  /**
   * @param array $ingredients must be an array of Ingredient
   */
  public function setIngredients(array $ingredients):self{
    //check $ingredients validity
    foreach ($ingredients as $ingredient){
      if (!$ingredient instanceof Ingredient){
        throw new Exception("les ingrédients peuvent uniquement être un array d'Ingredient");
      }
    }

    $this->ingredients = $ingredients;
    return $this;
  }
  /**
   * @param array $categories must be an array of Category
   */
  public function setCategories(array $categories):self{
    //check $categories validity
    foreach ($categories as $category){
      if (!$category instanceof Category){
        throw new Exception("les catégories peuvent uniquement être un array de Category");
      }
    }

    $this->categories = $categories;
    return $this;
  }
  /**
   * @param array $comments must be an array of Comment
   */
  public function setComments(array $comments):self{
    //check $comments validity
    foreach ($comments as $comment){
      if (!$comment instanceof Comment){
        throw new Exception("les commentaires peuvent uniquement être un array de Comment");
      }
    }

    $this->comments = $comments;
    return $this;
  }
}
