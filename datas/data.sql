START TRANSACTION;
use choco;
INSERT INTO `category`(`category`)
VALUES("Cake"),
("Gâteau"),
("Glace"),
("Biscuit"),
("Mousse"),
("Fruit"),
("Fondant");

INSERT INTO `ingredient_unity`(`unit`)
VALUES("g"),
("ml"),
("cl"),
("dl"),
("ampoule"),
("noisette"),
("cac"), -- Cuillière a café
("cas"), -- Cuillère a soupe
("kg");

INSERT INTO `ingredient`(`ingredient`, `image_url`)
VALUES("pomme", NULL),
("framboise", "https://img.cuisineaz.com/70x47/ingredients/all/framboise-80.jpg"),
("cerneau de noix", "https://img.cuisineaz.com/70x47/ingredients/all/cerneaux-de-noix-585.jpg"),
("noix", "https://img.cuisineaz.com/70x47/ingredients/all/cerneaux-de-noix-585.jpg"),
("noix de coco", NULL),
("sel", NULL),
("sucre", NULL),
("sucre brun", NULL),
("sucre roux", "https://img.cuisineaz.com/70x47/ingredients/all/sucre-roux-201.jpg"),
("sucre granulé", NULL),
("cacao", NULL),
("eau", NULL),
("blanc d'oeuf", NULL),
("jaune d'oeuf", NULL),
("oeuf", "https://img.cuisineaz.com/70x47/ingredients/all/oeuf-141.jpg"),
("oeuf large", "https://img.cuisineaz.com/70x47/ingredients/all/oeuf-141.jpg"),
("farine", "https://img.cuisineaz.com/70x47/ingredients/all/farine-2313.jpg"),
("lait", NULL),
("crème", NULL),
("crème fraiche", NULL),
("pépites de chocolat", NULL),
("levure chimique", NULL),
("bicarbonate de soude", NULL),
("extrait de vanille", NULL),
("essence d'amande", NULL),
("essence de rhum", NULL),
("chocolat noir 60% cacao", "https://img.cuisineaz.com/70x47/ingredients/all/chocolat-1076.jpg"),
("chocolat noir", "https://img.cuisineaz.com/70x47/ingredients/all/chocolat-1076.jpg"),
("chocolat menager", "https://img.cuisineaz.com/70x47/ingredients/all/chocolat-1076.jpg"),
("chocolat pâtissier", "https://img.cuisineaz.com/70x47/ingredients/all/chocolat-1076.jpg"),
("huile", NULL),
("beurre salé", NULL),
("beurre doux", NULL),
("beurre ramoli", NULL),
("beurre végétal", NULL),
("beurre", "https://img.cuisineaz.com/70x47/ingredients/all/beurre-227.jpg"),
("lait entier", NULL),
('lait sans lactose', NULL),
('lait demi écrémé', NULL),
('gousse de vanille', NULL),
('crème liquide entière', NULL);

COMMIT;