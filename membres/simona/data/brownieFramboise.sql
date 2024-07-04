START TRANSACTION;
INSERT INTO user(name, password, is_admin)
VALUES('simona', 'simona123', 1);

SET @user_id = LAST_INSERT_ID();

INSERT INTO recipe(`name`, `description`,`image_url`, `nb_people`, `preparation_time`, `cooking_time`)
VALUES("Brownie Framboise", "Découvrez la meilleure recette de Brownie Framboise, un dessert décadent et fruité. Apprenez à préparer ce délicieux brownie au chocolat et framboises, parfait pour toutes les occasions et pour tous les amateurs de chocolat !", "\..\public\assets\nouvelles img\brownieFramboise.jpg", 8, 60, 30);

SET @recipe_id = LAST_INSERT_ID();

INSERT INTO sub_recipe(recipe_id, title, sub_recipe_number)
VALUES (@recipe_id , "Préparation du brownie :", 1);

SET @sub_recipe_id1 = LAST_INSERT_ID();

INSERT INTO instruction(sub_recipe_id, text_content)
VALUES 
(@sub_recipe_id1, "Préchauffez le four à 180°C. Faites fondre le chocolat et le beurre au bain-marie."),
(@sub_recipe_id1, "Ajoutez le sucre et mélangez bien."),
(@sub_recipe_id1, "Ajoutez les œufs un à un en mélangeant bien entre chaque ajout."),
(@sub_recipe_id1, "Incorporez la farine et mélangez jusqu'à obtenir une pâte homogène."),
(@sub_recipe_id1, "Versez la moitié de la pâte dans un moule et répartissez les framboises dessus. Recouvrez avec le reste de la pâte."),
(@sub_recipe_id1, "Enfournez pendant 25-30 minutes.");


INSERT INTO ingredient_has_recipe(recipe_id, ingredient_id, ingredient_unity_id, quantity)
VALUES(@recipe_id , 17, 1, 150),  
(@recipe_id , 28, 1, 250),  
(@recipe_id , 2, 1, 150),   
(@recipe_id , 3, 1, 100),   
(@recipe_id , 15, NULL, 3), 
(@recipe_id , 9, 1, 150),
(@recipe_id , 36, 1, 170); 

INSERT INTO comment(recipe_id, user_id, subject, comment, created_date, stars)
VALUES(@recipe_id, @user_id, "Délicieux !!!", "Ce brownie est absolument délicieux ! La recette est facile à suivre et le résultat est parfait. Merci !", NULL, 5),
(@recipe_id, @user_id, "Incroyable !!!", "Le Brownie Framboise est devenu mon dessert préféré. Le chocolat et les framboises se marient parfaitement. Bravo pour cette recette !", NULL, 5);

INSERT INTO recipe_has_category(recipe_id, category_id)
VALUES(@recipe_id, 1); 

COMMIT;
