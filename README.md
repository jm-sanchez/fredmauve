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

21/06
- Ajout de la verification reCAPTCHA v3 pour le formulaire de contact (contact/index.html.twig et ContactFormType)

22/06
- Création de l'entité ImageNews et du crud associé (crud à supprimer) et mise en relation avec l'entité News (OneToOne)
- Modification des methodes show update delete de NewsDashboardController ainsi que tous les twigs associés
 Erreur: la suppression de news ne fonctionne pas, à corriger
- mise en commentaire des anciens boutons/formulaires pour la gestion de news dans les twigs newsDashboard (à supprimer si plus besoin)

23/06
- Séparation du formulaire et du button de suppression dans de nouveaux fichiers. Remplacement en include _form et _delete_form
- Modification des entités News et ImageNews (Relation OneToOne -> OneToMany). On a aussi adapté les différentes méthodes dans l'entité ImageNews et les templates (ajout des boucles for).
- Changement de la variable twig "categoryForm" à "form"

03/07
- Modification de la vue du formulaire de contact :
    - Ajout de la case à cocher sur l'utilisation des données saisies par l'utilisateur
    - Modification du fichier "FormType" et "contact.css"
- Intégration de reCAPTCHA v3 de Google grâce au bundle KarserRecaptcha3Bundle

07/07 (Juan)
- Modification de la vue du fomulaire de login:
    - Le message d'erreur s'affiche en français et dans la partie supérieure interne dur formulaire.
    - Ajout de la case à cocher "se souvenir de moi"
- Configuration de la langue de l'application (config/packages/translation.yaml)
- Changement de la route d'accès une fois que l'admin est connecté (src/Security/AppAuthenticator.php)
- Modification de la vue de l'index du contact_dashboard

10/07
- Modification de tous les templates de WorkDashboard
- Ajout de contraintes dans l'entité Work le Slug est désormais null


//================================//
+++ Modifications après le stage +++
//================================//

15/11
- Création du CartService, CartController et ses templates : index, success et validate
- Ajout de l'icône "panier" dans la navbar. NOTE: Pour que ça marche correctement il faut ajouter la variable  - 'cart' => $cartService->getTotal() - dans la méthode index dans chaque controlleur pour ne pas avoir une erreur.
- Ajout du fichier cart.js pour traiter le message flash.
- Élimination du BoutiqueController et de son template qui étaient inutilisés.
- Adaptation du ShopController pour afficher les oeuvres qui possèdent la propriété "saleable" activée.
- Affichage des oeuvres vendables dans la boutique (fichiers TWIG et CSS).
- Template "validate" pour la validation de payement (TWIG et CSS).
- Création de dossier "payment_method" dans les assets pour stocker les images des modes de paiement.
- Création des entités "Customer" et "OrderDetails"
- Création du formulaire "CustomerFromType" pour l'ajout des coordonnées du client avant de confirmer la commande et passer à STRIPE.
- Mise en place des contraintes de validation sur l'entité "Customer"
- Installation de la librairie stripe "composer require stripe/stripe-php"
- Mis en place du système de paiement (STRIPE) dans le fichier "PaymentController"
- Mise en place du système d'envoi de mail (MAILTRAP). Il sert à communiquer à l'admin et au client que la commande à été confirmée.
    - NOTE: pour que ce système marche correctement, j'ai dû commenter la ligne 19 (routing des emails) dans le fichier "config/packages/messenger.yaml" pour empêcher l'envoi de mails en mode async. De cette façon, les mails sont interceptés par MailTrap et donc visibles dans l'inbox du compte MailTrap.
- Création d'un dossier "emails" avex les deux templates qui contiennent les infos des commandes confirmées (Admin et Client).

17/11
- Optimisation du PaymentController (création de plusieurs méthodes pour séparer les tâches)
- Création du LegalController (et ses différents templates) pour le contrôle et affichage des méntions légales, politique de confidentialité, conditions générales de vente et crédits.
