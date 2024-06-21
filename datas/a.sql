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
