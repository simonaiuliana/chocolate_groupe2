<?php

require_once("SubRecipe.php");
require_once("Instruction.php");
require_once("Ingredient.php");
require_once("Comment.php");
require_once("Category.php");

class Recipe{
  const SEPARATOR_DB = '|||';
  private int $id;
  private string $name;
  private string $description;
  private int $nb_people;
  private int $preparation_time;
  private int $cooking_time;
  private int $rest_time;
  private ?string $img_url;
  // array of type Instruction
  private array $sub_recipes;
  // array of type Ingredient
  private array $ingredients;
  // array of type Category
  private array $categories;
  // array of type Comments
  private array $comments;

  public function __construct(int $id, string $name, string $description, int $nb_people, int $preparation_time, int $cooking_time, int $rest_time, ?string $img_url, array $sub_recipes, array $ingredients, array $categories, array $comments) {
    $this->setId($id);
    $this->setName($name);
    $this->setDescription($description);
    $this->setNbPeople($nb_people);
    $this->setPreparationTime($preparation_time);
    $this->setCookingTime($cooking_time);
    $this->setRestTime($rest_time);
    if ($img_url)$this->setImgUrl($img_url);
    $this->setSubRecipes($sub_recipes);
    $this->setIngredients($ingredients);
    $this->setCategories($categories);
    $this->setComments($comments);
  }

  public static function getRecipeById(PDO $db, int $id):self|string{
    $sql = "
      SELECT * FROM `recipe` r
      LEFT JOIN (
        -- ingredients
        SELECT
          GROUP_CONCAT(ing.id SEPARATOR '|||') AS ingredients_ids,
          GROUP_CONCAT(IFNULL(inu.unit, 'null') SEPARATOR '|||') AS ingredients_units,
          GROUP_CONCAT(ing.ingredient SEPARATOR '|||') AS ingredients,
          GROUP_CONCAT(IFNULL(ing.image_url, 'null') SEPARATOR '|||') AS ingredients_images,
          GROUP_CONCAT(inr.quantity SEPARATOR '|||') AS ingredients_quantities,
          inr.recipe_id AS inr_recipe_id
        FROM `ingredient_has_recipe` inr
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
        LEFT JOIN `recipe_has_category` rc ON rc.category_id=cat.id
        GROUP BY rc.recipe_id
      ) cat ON cat.rc_recipe_id=r.id
      LEFT JOIN (
        -- comments
        SELECT
          GROUP_CONCAT(com.id SEPARATOR '|||') AS comments_ids,
          GROUP_CONCAT(com.comment SEPARATOR '|||') AS comments,
          GROUP_CONCAT(com.subject SEPARATOR '|||') AS subjects,
          GROUP_CONCAT(com.created_date SEPARATOR '|||') AS comments_created_dates,
          GROUP_CONCAT(com.stars SEPARATOR '|||') AS comments_stars,
          GROUP_CONCAT(u.name SEPARATOR '|||') AS comments_username,
          com.recipe_id AS com_recipe_id
        FROM `comment` com
        LEFT JOIN `user` u ON com.user_id=u.id
        GROUP BY com.recipe_id
      ) com ON com.com_recipe_id=r.id
      WHERE id=$id;
    ";
    try {
      $query = $db->query($sql);
      $recipe = $query->fetch(PDO::FETCH_ASSOC);
      $query->closeCursor();
      if (!$recipe["id"]) return "recette non trouvée";
    }catch (Exception $e){
      return $e->getMessage();
    }
    /*get returned values*/
    /* recipe */
    $recipe_id = $recipe["id"];
    $recipe_name = $recipe["name"];
    $recipe_description = $recipe["description"];
    $recipe_nb_people = $recipe["nb_people"];
    $recipe_img_url = $recipe["image_url"];
    $recipe_preparation_time = $recipe["preparation_time"];
    $recipe_cooking_time = $recipe["cooking_time"];
    $recipe_rest_time = $recipe["rest_time"];
    /* ingredients */
    $ingredients_ids = $recipe["ingredients_ids"] ? explode(self::SEPARATOR_DB, $recipe["ingredients_ids"]) : [];
    $ingredients_units = $recipe["ingredients_units"] ? explode(self::SEPARATOR_DB, $recipe["ingredients_units"]) : [];
    $ingredients_names = $recipe["ingredients"] ? explode(self::SEPARATOR_DB, $recipe["ingredients"]) : [];
    $ingredients_images = $recipe["ingredients_images"] ? explode(self::SEPARATOR_DB, $recipe["ingredients_images"]) : [];
    $ingredients_quantities = $recipe["ingredients_quantities"] ? explode(self::SEPARATOR_DB, $recipe["ingredients_quantities"]) : [];
    /* categories */
    $categories_ids = $recipe["categories_ids"] ? explode(self::SEPARATOR_DB, $recipe["categories_ids"]) : [];
    $categories_names = $recipe["categories"] ? explode(self::SEPARATOR_DB, $recipe["categories"]) : [];
    /* comments */
    $comments_ids = $recipe["comments_ids"] ? explode(self::SEPARATOR_DB, $recipe["comments_ids"]) : [];
    $comments_texts = $recipe["comments"] ? explode(self::SEPARATOR_DB, $recipe["comments"]) : [];
    $comments_subjects = $recipe["subjects"] ? explode(self::SEPARATOR_DB, $recipe["subjects"]) : [];
    $comments_created_dates = $recipe["comments_created_dates"] ? explode(self::SEPARATOR_DB, $recipe["comments_created_dates"]) : [];
    $comments_stars = $recipe["comments_stars"] ? explode(self::SEPARATOR_DB, $recipe["comments_stars"]) : [];
    $comments_usernames = $recipe["comments_username"] ? explode(self::SEPARATOR_DB, $recipe["comments_username"]) : [];

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
      array_push($recipe_comments, new Comment($comments_ids[$i], $comments_texts[$i], $comments_subjects[$i], $comments_created_dates[$i], $comments_stars[$i], $comments_usernames[$i]));
    }

    /* sub_recipe */

    $sql = "
      -- sub_recipe
      SELECT 
        subr.*,
        GROUP_CONCAT(ins.id ORDER BY ins.instruction_number SEPARATOR '|||') AS instructions_ids,
        GROUP_CONCAT(ins.text_content ORDER BY ins.instruction_number SEPARATOR '|||') AS instructions,
        GROUP_CONCAT(IFNULL(ins.image_url, 'null') ORDER BY ins.instruction_number SEPARATOR '|||') AS instructions_image_url
      FROM `sub_recipe` AS subr
      LEFT JOIN `instruction` AS ins ON ins.sub_recipe_id = subr.id
      WHERE subr.recipe_id = $id
      GROUP BY subr.id
      ORDER BY subr.sub_recipe_number;
    ";

    try {
      $query = $db->query($sql);
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      $query->closeCursor();
    }catch (Exception $e){
      return $e->getMessage();
    }
    
    // $result contains the sub_recipes SQL
    $sub_recipes = [];
    for ($i=0;$i<sizeof($result);$i++){
      $sub_recipe = $result[$i];
      /* instructions */
      $instructions_ids = $sub_recipe["instructions_ids"] ? explode(self::SEPARATOR_DB, $sub_recipe["instructions_ids"]) : [];
      $instructions_texts = $sub_recipe["instructions"] ? explode(self::SEPARATOR_DB, $sub_recipe["instructions"]) : [];
      $instructions_imgs = $sub_recipe["instructions_image_url"] ? explode(self::SEPARATOR_DB, $sub_recipe["instructions_image_url"]) : [];
      $sub_recipe_instructions = [];
      for ($i=0;$i<sizeof($instructions_ids);$i++){
        array_push($sub_recipe_instructions, new Instruction($instructions_ids[$i], $instructions_texts[$i], $instructions_imgs[$i]));
      }
      array_push($sub_recipes, new SubRecipe($sub_recipe["id"], $sub_recipe["title"], $sub_recipe["image_url"], $sub_recipe["preparation_time"], $sub_recipe_instructions));
    }

    return new Recipe($recipe_id, $recipe_name, $recipe_description, $recipe_nb_people, $recipe_preparation_time, $recipe_cooking_time, $recipe_rest_time, $recipe_img_url, $sub_recipes, $recipe_ingredients, $recipe_categories, $recipe_comments);
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
  /**
   * @return true if success , string if error
   */
  public static function create(PDO $db, string $name, string $description, int $nb_people, int $preparation_time, int $cooking_time, int $rest_time, ?string $img_url):string|bool{
    $name = trim(htmlspecialchars(strip_tags($name)),ENT_QUOTES);
    if ($img_url)$img_url = trim(htmlspecialchars(strip_tags($img_url)),ENT_QUOTES);

    try {
      $sql = "
        INSRT INTO `recipe`(`name`, `description`, `nb_people`, `preparation_time`, `cooking_time`, `rest_time`, `img_url`)
        VALUES(?,?,?,?,?,?,?);
      ";
      $prepare = $db->prepare($sql);
      $prepare->execute([$name, $description, $nb_people, $preparation_time, $cooking_time, $rest_time, $img_url]);
      $prepare->closeCursor();
      return true;
    }catch (Exception $e){
      return $e->getMessage();
    }
  }

  /**
   * @return true if success , string if error
   */
  public function update(PDO $db):string|bool{
    try {
      $sql = "
        UPDATE `recipe`
        SET
          `name`=?,
          `description`=?,
          `nb_people`=?,
          `preparation_time`=?,
          `cooking_time`=?,
          `rest_time`=?,
          `img_url`=?
        WHERE
          `id`=?
      ";
      $prepare = $db->prepare($sql);
      $prepare->execute([$this->name, $this->description, $this->nb_people, $this->preparation_time, $this->cooking_time, $this->rest_time, $this->img_url, $this->id]);
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
      $sql = "DELETE FROM `recipe` WHERE id=?;";
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
  public function getName():string{
    return $this->name;
  }
  public function getDescription():string{
    return $this->description;
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
  public function getRestTime():int{
    return $this->rest_time;
  }
  public function getImgUrl():?string{
    return $this->img_url;
  }
  /**
   * @return array $instructions -> array of Instruction
   */
  public function getSubRecipes():array{
    return $this->sub_recipes;
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
  public function setDescription(string $description):self{
    $this->description = trim(htmlspecialchars(strip_tags($description)),ENT_QUOTES);
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
  public function setRestTime(int $rest_time):self{
    $this->rest_time = $rest_time;
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
   * @param array $sub_recipes must be an array of SubRecipe
   */
  public function setSubRecipes(array $sub_recipes):self{
    //check $instructions validity
    foreach ($sub_recipes as $sub_recipe){
      if (!$sub_recipe instanceof SubRecipe){
        throw new Exception("les sub_recipes peuvent uniquement être un array de SubRecipe");
      }
    }

    $this->sub_recipes = $sub_recipes;
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
