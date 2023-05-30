# fredmauve

PROJET FRED MAUVE (ARTISTE - ILLUSTRATEUR)

- Nous avons choisi de travailler avec le framework Symfony dans la version 5.4.22, avec PHP 7.4.33 et Bootstrap v5.3.
- Création du projet en passant par la commande : symfony new --webapp fredmauve

1. Création de plusieurs dossiers et fichiers dans "public"
    - Ajout de la police qu'on a vu sur WP
    - Dossier media/images avec deux images que j'ai pris du facebook de Fred
    - création du fichier style css pour pouvoir afficher la police dans le site

2. J'ai ajouté un footer avec le contenu de la navbar et les réseaux sociaux
3. et j'ai modifié la page d'accueil (le minimum)
4. J'ai créé l'entité Contact et le ContactTypeForm pour pouvoir construire le formulaire.
5. L'affichage du formulaire de contact est construit.

21/05
- Modification de la vue sur page d'accueil et la page de contact
- Création de la bdd "fred" pour vérifier si les données du formulaire de contact sont correctement ajoutées.
- Mise en place du fonctionnement du message flash dans le ContactController qui s'affiche dans la vue de contact
- Validation du formulaire de contact via l'entité Contact

24/05
- Présentation de la maquette
    Redaction du cahier de charges
    Le client nous remarque l'importance de l'affichage de l'image dans la galerie. Elle doit contenir des images miniatures qui représenteront les détails de chaque œuvre.

25/05
- Création de l'entité Admin
    commande : symfony console make:user Admin
    Ceci nous a créé :
        * Entity/Admin.php
        * Repository/AdminRepository.php
- Création de l'espace de Connexion (login)
    commande : symfony console make:auth
    Ceci nous a créé :
        * src/Security/AppAuthenticator.php
        * templates/security/login.html.twig
        *
- Création des fixtures pour définir les données de l'admin
    commande : symfony console make:fixtures

- Création des entités Work et News
- Mise en place des relations entre les tables (Work, News et Contact)
    Toutes les tables sont réliées à Admin. Pour cette raison, chaque table possède une clé étrangère "administrator".
- Répartition des tâches (ne pas toutes):
    - Juan : page d'accueil, de contact et de la boutique (y compris l'affichage de l'image cliquée).
    - Loïc : page de la biografie, des actualités et de la galerie (y compris l'affichage de l'image cliquée).

31/05
- Solution du problème de relation entre les tables.
    - Sur les entités, les clés étrangères étaient en minuscule et il fallait qu'elles étaient en mayascule.
        Exemple : targetEntity=admin::class - a été transformé à -
                  targetEntity=Admin::class
- Modification de l'affichage sur la base lorsque l'admin est connecté.
    - Affichage du bouton de "Se connecter" lorque l'admin n'est pas connexté
    - Affichage du bouton de "Déconnexion" dans le cas inverse.