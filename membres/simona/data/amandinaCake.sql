START TRANSACTION;
INSERT INTO `user`(`name`, `password`, `is_admin`)
VALUES('simona', 'simona123', 1);

SET @user_id = LAST_INSERT_ID();

INSERT INTO `recipe`(`name`, `description`, `nb_people`, `image_url`, `preparation_time`, `cooking_time`, `rest_time`)
VALUES("Amandina Cake", "Voici la meilleure recette de l'Amandina Cake, un dessert classique et raffiné. Pas à pas, vous apprendrez à préparer ce délicieux gâteau, au dessus moelleux au cacao, siropé au rhum et au chocolat fin et crème au beurre. Parfait pour toutes les occasions et pour tous les goûts !", 10, "\..\public\assets\nouvelles img\amandina.jpg", 90, 45, 0);

SET @recipe_id = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `sub_recipe_number`)
VALUES (@recipe_id , "Préparation de la génoise :", 1);

SET @sub_recipe_id1 = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `sub_recipe_number`)
VALUES (@recipe_id , "Préparation de la crème :", 2);

SET @sub_recipe_id2 = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `sub_recipe_number`)
VALUES (@recipe_id , "Préparation de sirop :", 3);

SET @sub_recipe_id3 = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `sub_recipe_number`)
VALUES (@recipe_id , "Préparation de glaçage :", 4);

SET @sub_recipe_id4 = LAST_INSERT_ID();

INSERT INTO `instruction`(`sub_recipe_id`, `text_content`)
VALUES 
(@sub_recipe_id1, "Séparez les œufs. Battez les blancs avec une pincée de sel jusqu'à ce qu'ils soient fermes. Ajoutez le sucre progressivement en continuant de battre."),
(@sub_recipe_id1, "Mélangez les jaunes d'œufs avec l'huile et ajoutez-les délicatement aux blancs en neige."),
(@sub_recipe_id1, "Incorporez la farine tamisée avec le cacao en mélangeant délicatement."),
(@sub_recipe_id1, "Versez la pâte dans un moule et enfournez à 180°C pendant environ 25 minutes."),
(@sub_recipe_id2, "Faites fondre le chocolat au bain-marie. Laissez-le tiédir légèrement."),
(@sub_recipe_id2, "Battez le beurre jusqu'à ce qu'il devienne crémeux. Ajoutez progressivement le sucre en poudre tout en continuant de battre."),
(@sub_recipe_id2, "Incorporez le chocolat fondu au mélange de beurre et de sucre jusqu'à obtenir une crème homogène."),
(@sub_recipe_id3, "Faites fondre délicatement le sucre pour ne pas trop le brûler, ajoutez de l'eau chaude et faites bouillir jusqu'à ce que le sucre soit fondu. Quand il fait froid, ajoutez l'essence de rhum."),
(@sub_recipe_id4, "Faire fondre le chocolat au bain de vapeur ou au micro-ondes avec l'huile.");

INSERT INTO `ingredient_has_recipe`(`recipe_id`, `ingredient_id`, `ingredient_unity_id`, `quantity`)
VALUES(@recipe_id , 15, NULL, 6),  -- Replace with actual ingredient_id, unity_id, and quantity for flour
(@recipe_id , 7, 1, 330),  -- Replace with actual ingredient_id, unity_id, and quantity for sugar
(@recipe_id , 31, 2, 120),    -- Replace with actual ingredient_id, unity_id, and quantity for eggs
(@recipe_id , 6, 1, 2),   -- Replace with actual ingredient_id, unity_id, and quantity for cocoa powder
(@recipe_id , 11, 1, 100),  -- Replace with actual ingredient_id, unity_id, and quantity for butter
(@recipe_id , 17, 1, 130);  -- Replace with actual ingredient_id, unity_id, and quantity for chocolate
(@recipe_id , 27, 1, 250),  -- Replace with actual ingredient_id, unity_id, and quantity for sugar
(@recipe_id , 29, 1, 100),    -- Replace with actual ingredient_id, unity_id, and quantity for eggs
(@recipe_id , 36, 1, 200),   -- Replace with actual ingredient_id, unity_id, and quantity for cocoa powder
(@recipe_id , 12, 2, 350),  -- Replace with actual ingredient_id, unity_id, and quantity for butter
(@recipe_id , 26, 5, 2); 

INSERT INTO `comment`(`recipe_id`, `user_id`, `subject`, `comment`, `created_date`, `stars`)
VALUES(@recipe_id, @user_id, "Délicieux !!!", "Ce gâteau est absolument délicieux ! La recette est facile à suivre et le résultat est parfait. Merci !", NULL, 5),
(@recipe_id, @user_id, "Incroyable !!!", "L'Amandina Cake est devenu mon gâteau préféré. La génoise est moelleuse et la crème est divine. Bravo pour cette recette !", NULL, 5);

INSERT INTO `recipe_has_category`(`recipe_id`, `category_id`)
VALUES(@recipe_id, 2); 

COMMIT;
