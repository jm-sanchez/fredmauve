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
    - Affichage du lien "dashboard" dans la navbar
- Correction du problème avec la clé étrangère dans le controller Contact

01/06
- Création du formulaire (sans html/css) d'ajout des News
- Affichage des News dans le dashboard (sans html/css)
- Ajout des maquettes pour le dashboard (sur Figma)
- Modification de la navbar dans base.html.twig (version test)
- Ajouter de la navbar dans le dashboard (à améliorer)

02-06
- Ajout du dossier templates/_partials pour une meilleur séparation de nos élément twig
- Modification de la page dashboard, dashboard/news (la table est affiché en fonction de la bdd) et ajout du bouton de lien vers ajouter une actualité.

05-06
- Ajout des méthodes delete, update et show dans newsDashboardController ainsi que les vues associées (Templates/dashboard/news/show et update)
- Ajout des modales pour le delete du newsDashboardController avec le js associés (Le js nous a servi à transférer l'id de la news dans le modal de confirmation car si on fait le modal directement dans la boucle de création on surcharge le code)
- Ajout des méthodes delete et show dans contactDashboardController ainsi que les vues associées, la modal et le js (copier coller des methodes newsDashboardController et modale)

06-06
- Modification de notre modèle relationnelle
    - Création de l'entité/table Category et mise en relation de celle-ci vers l'Admin et Work.
- Création du CRUD pour l'entité Work (Il nous reste à faire le CRUD pour l'entité Category)
- Ajout de la propriété "slug" dans les entité Work, Category et News.
- Plusieurs corrections et quelques chagements dans les fichiers css (mis en place des éffets de "hover", taille de police) et dans les fichiers html de la base, du home et du contact.

07/06
- Création du CRUD pour l'entité Category. On a mis en place la vue et on a corrigé les routes.
- Correction des erreurs de la vue du CRUD Work (relation avec categorie)
- On a remplacé le persist et le flush par la méthode "add" dans le NewsDashboardController.
- Modification de la route de la modale dans les fichiers "newsDashboard.js" et "contactDashboard.js".
- A FAIRE :
    - création des modales pour les CRUDs Category et Work
    - Gérer le téléchargement de l'image et image-détail pour l'entité Work et News.

08/06
- Changement de la vérification de l'id admin en vérification role_admin dans ContactController, WorkDashboardController, NewsDashboardController et CategoryDashboardController
- Suppression de la méthode et de la vue "success" dans Contact
- Modification de la vue de la page Contact

09/06
- A ajouter: bouton delete pour les catégorie dans le dashboard
- Changement de la vérification du role_admin en vérification email de l'admin dans ContactController, WorkDashboardController, NewsDashboardController et CategoryDashboardController
- Création de l'entité Image et de la relation avec Work
- Modification du twig formulaire '_form' dans work_dashboard et ajout du champ image dans le formulaire Work
- Ajout du paramètre "image_directory" dans le fichier services.yaml
- Création du service 'PictureService' pour enregister une image dans le dossier upload
- Ajout de cascade={"persist"} dans la propriété image de l'entité Work (à corriger)

12/06
- On a ajouter le message flash dans le formulaire d'ajout d'une œuvre et on a mis la condition if dans le WorkDashboardController (à faire en Ajax pour que le formulaire d'ajout d'oeuvres' reste rempli en cas d'erreur)
- Ajout de la modification d'image dans la méthode updateWork dans WorkDashboardController. Le template est aussi fonctionnel.
- Ajout des boutons de suppression d'image (méthode à modifier dans le WorkDashboardController)
- Ajout de la fonction delete dans le ServicePicture
- Ajout de la vérification et du message pour l'ajout d'image dans le WorkDashboardController dans la méthode addWork

19/06
- Ajout de la suppression des images et miniatures dans la méthode de suppression d'une oeuvre (via controller workDashboard et ajax dans images.js); 
Erreurs : 
    Soucis de redirection après la suppression d'une image à corriger. 
    Le formulaire disparait après la suppression d'une image dans le template update du dossier work_dashboard.

20/06
- Corrections : 
    Soucis de redirection après la suppression d'une image corrigé (Ajout d'une condition if empty)
    Le formulaire disparait après la suppression d'une image corrigé (l'image et le lien de suppression on été déplacés dans une div)
- Suppression de la propriété Media de l'entité News