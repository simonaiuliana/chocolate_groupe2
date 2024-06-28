INSERT INTO `user`(`name`, `password`, `is_admin`)
VALUES('pomme', 'pomme123', 1),
('poire', 'poire123', 0);

INSERT INTO `recipe`(`name`, `nb_people`, `image_url`, `preparation_time`, `cooking_time`)
VALUES('pomme au choco', 4, 'http://www.pomme.com/image_de_pomme_au_choco', 45, 40);

INSERT INTO `sub_recipe`(`recipe_id`, `title`, `image_url`, `preparation_time`, `sub_recipe_number`)
VALUES
    (1, 'Préparer la pomme', 'http://www.pomme.com/image_de_pomme_au_choco', 45, 1),
    (1, 'Manger la pomme', 'http://www.pomme.com/image_de_pomme_au_choco', 5, 2),
    (1, 'Recracher la pomme', 'http://www.pomme.com/image_de_pomme_au_choco', 1, 3),
    (2, 'Bah voilà', 'http://www.pomme.com/image_de_pomme_au_choco', 2, 1);

INSERT INTO `instruction`(`sub_recipe_id`, `text_content`, `image_url`, `instruction_number`)
VALUES 
(1, 'mettre la pomme sur l\'assiette', NULL, 1),
(1, 'Regarder la pomme', NULL, 2),
(1, 'Couper la pomme au choco', NULL, 3),
(2, 'manger la pomme au choco', NULL, 1),
(3, 'Recracher la pomme', NULL, 1),
(4, 'Ché plus', NULL, 1);

INSERT INTO `ingredient`(`ingredient`, `image_url`)
VALUES('pomme', 'pomme.com/belle_image_de_pomme'),
('choco', 'choco.com/belle_image_de_choco');

INSERT INTO `ingredient_unity`(`unit`)
VALUES('g'),
('ml'),
('kg');

INSERT INTO `ingredient_has_recipe`(`recipe_id`, `ingredient_id`, `ingredient_unity_id`, `quantity`)
VALUES(1, 1, NULL, 1),
(1, 2, 1, 200);

INSERT INTO `comment`(`recipe_id`, `user_id`, `comment`, `created_date`, `stars`)
VALUES(1, 1, 'cannibalisme!!!', NULL, 1),
(1, 2, 'délicieux!!!', NULL, 5);

INSERT INTO `category`(`category`)
VALUES('pomme'),
('chocolat'),
('vegan'),
('fruit');

INSERT INTO `recipe_has_category`(`recipe_id`, `category_id`)
VALUES(1, 1),
(1, 2),
(1, 3),
(1, 4);
