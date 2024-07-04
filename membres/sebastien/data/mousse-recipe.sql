START TRANSACTION;
INSERT INTO `user`(`name`, `password`, `is_admin`)
VALUES('seb', 'seb123', 1);
SET @user_id = LAST_INSERT_ID();
INSERT INTO `recipe`(`name`,`description`, `nb_people`, `image_url`, `preparation_time`, `cooking_time`, `rest_time`)
VALUES("Mouse au chocolat","\"Plongez dans un Nuage de Douceur : Découvrez Notre Recette Authentique de Mousse au Chocolat, une Harmonie Parfaite de Légèreté et de Fondant qui Transformera Chaque Cuillère en un Instant de Pur Bonheur\"", 4, "img/recipes/Mousse/mous4.jpeg", 30, 30,120);

SET @recipe_id = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Desserts Pure Chocomousse :", NULL, 30, 1);

SET @sub_recipe_id1 = LAST_INSERT_ID();


INSERT INTO `instruction`(`sub_recipe_id`, `text_content`, `image_url`, `instruction_number`)
VALUES 
(@sub_recipe_id1, "Faites fondre le chocolat au bain-marie avec le beurre.", NULL, 1),
(@sub_recipe_id1, "Incorporez 50 g de sucre aux jaunes d’œufs et battez-les. ", NULL, 2),
(@sub_recipe_id1, "Ajoutez le sucre restant aux blancs d’œufs et battez-les en neige.", NULL, 3),
(@sub_recipe_id1, "Fouettez également la crème jusqu’à ce qu’elle soit bien ferme.", NULL, 4),
(@sub_recipe_id1, "Plongez le bol sous l’eau froide et versez-y les jaunes d’œufs.", NULL, 5),
(@sub_recipe_id1, "Incorporez la crème fraîche, puis ajoutez délicatement les blancs d’œufs.", NULL, 6),
(@sub_recipe_id1, "Répartissez dans des verres et laissez-la durcir pendant 2 heures au frigo.", NULL, 7),
(@sub_recipe_id1, "Servez avec un biscuit en guise de garniture.", NULL, 8);


INSERT INTO `ingredient_has_recipe`(`recipe_id`, `ingredient_id`, `ingredient_unity_id`, `quantity`)
VALUES(@recipe_id , 28,1,400),
(@recipe_id , 36, 1,100),
(@recipe_id , 14, NULL,4),
(@recipe_id , 13, NULL,10),
(@recipe_id , 7, 1,100),
(@recipe_id , 20, 4,1),
(@recipe_id , 4, NULL, 4);

INSERT INTO `comment`(`recipe_id`, `user_id`, `subject`,`comment`, `created_date`, `stars`)
VALUES(@recipe_id, @user_id, "chocomous","cannibalisme!!!", NULL, 1),
(@recipe_id, @user_id,"suce", "délicieux!!!", NULL, 5);

INSERT INTO `recipe_has_category`(`recipe_id`, `category_id`)
VALUES(@recipe_id, 5);

COMMIT;