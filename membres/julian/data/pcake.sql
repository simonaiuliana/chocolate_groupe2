START TRANSACTION;
INSERT INTO `user`(`name`, `password`, `is_admin`)
VALUES('Admin', 'Admin123', 1);

INSERT INTO `recipe`(`name`, `nb_people`, `image_url`, `preparation_time`, `cooking_time`)
VALUES("Gâteau Murzynek", 4, "img/recipes/pcake/murzynekgal1.webp", 60, 720);

SET @recipe_id = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Tout d'abord préparons le gâteau :", "img/recipes/pcake/murzynekgal2.webp", 45, 1);

SET @sub_recipe_id1 = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Gateau Murzynek :", "img/recipes/glace/chan.webp", 15, 2);

SET @sub_recipe_id2 = LAST_INSERT_ID();


INSERT INTO `instruction`(`sub_recipe_id`, `text_content`, `image_url`, `instruction_number`)
VALUES 
(@sub_recipe_id1, "Dans une casserole, faire fondre 200 g de beurre à feu doux. Ajouter 200 g de sucre, 4 cuillères à soupe de cacao en poudre et 5 cuillères à soupe d'eau. Mélanger jusqu'à obtenir une consistance lisse. Porter à ébullition douce, puis retirer du feu et laisser refroidir.", NULL, 1),
(@sub_recipe_id1, "Préchauffer le four à 180°C (350°F).", NULL, 2),
(@sub_recipe_id1, "Séparer les blancs d'œufs des jaunes. Battre les blancs en neige ferme.", NULL, 3),
(@sub_recipe_id1, "Ajouter les jaunes d'œufs au mélange chocolaté refroidi et bien mélanger. Incorporer 1 cuillère à café d'extrait de vanille.", NULL, 4),
(@sub_recipe_id1, "Tamiser 200 g de farine et 2 cuillères à café de levure chimique, puis les ajouter progressivement au mélange chocolaté, en mélangeant jusqu'à obtenir une pâte homogène.", NULL, 5),
(@sub_recipe_id1, "Incorporer délicatement les blancs d'œufs en neige à la pâte.", NULL, 6),
(@sub_recipe_id1, "Verser la pâte dans un moule à gâteau beurré et fariné.", NULL, 7),
(@sub_recipe_id1, "Enfourner et cuire pendant environ 35-40 minutes, ou jusqu'à ce qu'un cure-dent inséré au centre en ressorte propre.", NULL, 8),
(@sub_recipe_id1, "Laisser refroidir le gâteau dans le moule pendant 10 minutes, puis le démouler sur une grille pour qu'il refroidisse complètement.", NULL, 9),
(@sub_recipe_id1, "(Facultatif) Faire fondre 100 g de chocolat noir au bain-marie ou au micro-ondes. Verser le chocolat fondu sur le gâteau refroidi et étaler uniformément à l'aide d'une spatule.", NULL, 10),
(@sub_recipe_id1, "(Facultatif) Laisser le glaçage prendre avant de découper et de servir. Décorer avec des copeaux de chocolat, des noix hachées ou du sucre glace.", NULL, 11);


INSERT INTO `ingredient_has_recipe`(`recipe_id`, `ingredient_id`, `ingredient_unity_id`, `quantity`)
VALUES(@recipe_id , 1, NULL, 1),
(@recipe_id , 2, 1, 200);

INSERT INTO `comment`(`recipe_id`, `user_id`, `comment`, `created_date`, `stars`)
VALUES(1, 1, "cannibalisme!!!", NULL, 1),
(1, 2, "délicieux!!!", NULL, 5);

INSERT INTO `recipe_has_category`(`recipe_id`, `category_id`)
VALUES(@recipe_id, 3);

COMMIT;