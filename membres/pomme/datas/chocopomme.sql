START TRANSACTION;
INSERT INTO `user`(`name`, `password`, `is_admin`)
VALUES('Pomme', 'Pomme', 1),
('poire', 'poire', 0);

INSERT INTO `recipe`(`name`, `nb_people`, `image_url`, `preparation_time`, `cooking_time`)
VALUES("Choco Pomme", 4, "img/img/recipes/chocopomme.jpg", 15, 15);

SET @recipe_id = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Préparation des pommes :",NULL, 5, 1);

SET @sub_recipe_id1 = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Préparation du chocolat :", NULL, 5, 2);

SET @sub_recipe_id2 = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Cuisson des pommes :", NULL, 15, 3);

SET @sub_recipe_id3 = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Cuisson au four :", NULL, 15, 4);

SET @sub_recipe_id4 = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Service :", NULL, 5, 5);

SET @sub_recipe_id5 = LAST_INSERT_ID();

INSERT INTO `instruction`(`sub_recipe_id`, `text_content`, `image_url`, `instruction_number`)
VALUES 
(@sub_recipe_id1, "Préchauffez votre four à 180°C (thermostat 6).", NULL, 1),
(@sub_recipe_id1, "Lavez les pommes et séchez-les avec du papier absorbant.", NULL, 2),
(@sub_recipe_id1, "Retirez le trognon des pommes à l’aide d’un vide-pomme.", NULL, 3),

(@sub_recipe_id2, "Cassez le chocolat en petits morceaux.", NULL, 1),
(@sub_recipe_id2, "Dans une casserole, faites fondre le chocolat avec un peu d’eau sur feu doux, en remuant régulièrement jusqu’à obtenir une sauce lisse.", NULL, 2),

(@sub_recipe_id3, "Placez les pommes dans un plat allant au four.", NULL, 1),
(@sub_recipe_id3, "Remplissez chaque pomme évidée avec une cuillère à café de sucre roux et une noisette de beurre végétal.", NULL, 2),
(@sub_recipe_id3, "Versez la sauce au chocolat fondue sur les pommes.", NULL, 3),

(@sub_recipe_id4, "Enfournez les pommes et laissez cuire pendant environ 15 minutes.", NULL, 1),
(@sub_recipe_id4, "Les pommes doivent être tendres mais pas trop molles.", NULL, 2),

(@sub_recipe_id5, "Servez les pommes chaudes, nappées de leur sauce au chocolat.", NULL, 1);

INSERT INTO `ingredient_has_recipe`(`recipe_id`, `ingredient_id`, `ingredient_unity_id`, `quantity`)
VALUES
(@recipe_id , 1, NULL, 4),
(@recipe_id , 30, 1, 100),
(@recipe_id , 9, 6, 4),
(@recipe_id , 35, 6, 4),
(@recipe_id , 12, 3, 10);

INSERT INTO `comment`(`recipe_id`, `user_id`, `comment`, `created_date`, `stars`)
VALUES(1, 1, "cannibalisme!!!", NULL, 1),
(1, 2, "délicieux!!!", NULL, 5);

COMMIT;