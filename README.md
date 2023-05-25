# fredmauve

Salut,

J'ai fait le suivant :

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
