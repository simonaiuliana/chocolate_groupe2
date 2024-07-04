START TRANSACTION;
use choco;
INSERT INTO `user`(`name`, `password`, `is_admin`)
VALUES('Kevin', 'Kevin123', 1);

SET @user_id = LAST_INSERT_ID();

INSERT INTO `recipe`(`name`, `description`, `nb_people`, `image_url`, `preparation_time`, `cooking_time`, `rest_time`)
VALUES("Glace au Chocolat", "Découvrez le plaisir d’une glace au chocolat riche et onctueuse, où la douceur de la crème anglaise se mêle à l’intensité du chocolat noir. Chaque bouchée est une célébration des saveurs, un équilibre parfait entre la fraîcheur de la glace vanille et la profondeur du chocolat.", 4, "img/recipes/glace/glace-chocolat.jpeg", 60, 0, 720);

SET @recipe_id = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Tout d'abord préparons la crème anglaise :", "img/recipes/glace/cream-anglaise-chocolat.webp", 45, 1);

SET @sub_recipe_id1 = LAST_INSERT_ID();

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES (@recipe_id , "Maintenant passons a la préparation de la crème chantilly :", "img/recipes/glace/chan.webp", 15, 2);

SET @sub_recipe_id2 = LAST_INSERT_ID();

INSERT INTO `instruction`(`sub_recipe_id`, `text_content`, `image_url`, `instruction_number`)
VALUES 
(@sub_recipe_id1, "Dans un <a href=\"https://www.maspatule.com/92-cul-de-poule-bol\" target=\"_blank\">cul de poule</a>, fouettez le sucre et les jaunes d’oeufs. Le mélange doit blanchir et doubler de volume.", NULL, 1),
(@sub_recipe_id1, "Faites bouillir le lait dans une <a href=\"https://www.maspatule.com/33-casserole\" target=\"_blank\">casserole</a>. <strong>Notre astuce de cooker</strong> : Faites infuser des grains de vanilles pendant 30 minutes dans le lait pour donner plus de profondeur en bouche à votre glace. ", NULL, 2),
(@sub_recipe_id1, "Versez le lait bouillant sur le mélange sucre + oeuf tout en mélangeant bien afin que les oeufs ne cuisent pas.", NULL, 3),
(@sub_recipe_id1, "Une fois le mélange bien homogène, reversez le tout dans la casserole et laissez cuire à feu doux quelques minutes.", NULL, 4),
(@sub_recipe_id1, "La crème doit épaissir légèrement et napper votre cuillère en bois. <strong>Notre astuce de cooker</strong> : Si vous faites un trait sur le dos de la cuillère et qu’il reste net, votre crème anglaise est prête !", NULL, 5),
(@sub_recipe_id1, "Versez la crème en 3 fois sur le chocolat fondu afin de bien l’incorporer", NULL, 6),
(@sub_recipe_id1, "Couvrez la préparation au contact avec un film alimentaire et placez-la au frais pendant 12 heures <i data-aos=\"flip-right\" data-aos-delay=\"500\" class=\"fa-solid fa-clock\"></i>.", NULL, 7),
(@sub_recipe_id2, "Montez la crème liquide entière en chantilly puis incorporez-la délicatement avec une maryse à la crème anglaise.", NULL, 1),
(@sub_recipe_id2, "Replacez la préparation au réfrigérateur quelques heures avant de mettre à turbiner en sorbetière pendant 40 à 45 minutes <i data-aos=\"flip-right\" data-aos-delay=\"500\" class=\"fa-solid fa-clock\"></i>.", NULL, 2),
(@sub_recipe_id2, "Réservez la glace au congélateur avant de servir.", NULL, 3);

INSERT INTO `ingredient_has_recipe`(`recipe_id`, `ingredient_id`, `ingredient_unity_id`, `quantity`)
VALUES(@recipe_id , 7, 1, 75),
(@recipe_id , 14, NULL, 4),
(@recipe_id , 37, 3, 40),
(@recipe_id , 28, 1, 120),
(@recipe_id , 40, NULL, 1),
(@recipe_id , 41, 3, 15);

INSERT INTO `comment`(`recipe_id`, `user_id`, `subject`, `comment`, `stars`)
VALUES(@recipe_id, @user_id, "Délicieux !!!", "Cette glace était tellement onctueuse ! Je dirais même savoureuse !! L'image de la crème anglaise au chocolat est vraiment fidèle a la réalité, j'ai eu exactement la même chose ! Continuez comme ça je vous aime 😊", 1),
(@recipe_id, @user_id, "Sublime !!!", "Je n'y croyais pas lorsque j'ai commencé la recette, mais une fois terminé et goûté je suis en extase... Je ne sais plus quoi faire, j'ai envie de tout manger TOUT MANGER JE BEGAILLE ACTUELLEMENT AAIIIDDEZZZ MOOIII J AI FAIIIMMM JJJJJ AAAIIII FFAIIIIMMMM !!!", 5);

INSERT INTO `recipe_has_category`(`recipe_id`, `category_id`)
VALUES(@recipe_id, 3);

COMMIT;