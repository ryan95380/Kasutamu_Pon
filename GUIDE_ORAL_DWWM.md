# Guide Oral DWWM - Kasutamu Pon

## 1. Présentation rapide du projet

Kasutamu Pon est un site e-commerce de figurines personnalisables inspirées de l'univers manga.
Le client peut consulter le catalogue, choisir une figurine, ajouter une personnalisation, remplir son panier et tester un paiement avec Stripe Checkout.
Le projet contient aussi une inscription, une connexion et une interface EasyAdmin.

### Ce que je peux dire en introduction

> J'ai développé Kasutamu Pon avec Symfony 7, PHP, Twig, Doctrine et MySQL. Le site permet de consulter des figurines, de les personnaliser puis de les ajouter dans un panier stocké en session. J'ai aussi intégré Stripe Checkout en mode test et EasyAdmin pour gérer les données de la boutique. Le projet fonctionne dans des conteneurs Docker avec PHP, Nginx et MySQL.

## 2. Fonctionnement général

Une requête suit généralement ce chemin :

1. Le navigateur demande une URL.
2. Une route Symfony appelle un Controller.
3. Le Controller utilise éventuellement un Repository.
4. Le Repository récupère des objets dans MySQL avec Doctrine.
5. Le Controller envoie les données à un template Twig.
6. Twig produit le HTML affiché dans le navigateur.

Mots-clés : `route`, `Controller`, `Repository`, `Entity`, `Twig`, `Response`.

## 3. Parcours utilisateur

1. L'utilisateur arrive sur `/accueil`.
2. Il ouvre `/catalogue` pour voir les figurines enregistrées en base.
3. Il clique sur `Personnaliser` pour ouvrir `/personnalisation/{id}`.
4. Le JavaScript modifie l'aperçu, le nom de l'option et le prix affiché.
5. Le produit personnalisé est ajouté au panier stocké dans la session.
6. L'utilisateur ouvre `/panier` et peut retirer un article.
7. Il clique sur `Passer au paiement`.
8. Symfony crée une session Stripe Checkout et redirige vers Stripe.
9. Après succès, le panier est vidé. Après annulation, il est conservé.

## 4. Rôle des technologies

### Symfony

Symfony organise l'application. Il gère les routes, les Controllers, les services, la session, la sécurité et les réponses HTTP.

> Symfony me donne une structure claire et évite de mélanger l'accès aux données, la logique PHP et l'affichage HTML.

### Doctrine

Doctrine fait le lien entre les objets PHP et les tables MySQL. Une Entity représente une table et un Repository sert à rechercher ses données.

> J'utilise Doctrine pour travailler avec des objets PHP plutôt que d'écrire toutes mes requêtes SQL à la main.

Mots-clés : `ORM`, `Entity`, `Repository`, `persist`, `flush`, `migration`, `relation`.

### Twig

Twig est le moteur de templates de Symfony. Il affiche les variables envoyées par les Controllers et permet les boucles et conditions.

> Twig sépare la présentation HTML de la logique PHP du Controller.

Mots-clés : `{{ variable }}`, `{% for %}`, `{% if %}`, `extends`, `block`, `path`, `asset`.

### MySQL

MySQL stocke les utilisateurs, figurines, personnalisations, commandes et paiements.

> MySQL assure la conservation des données même après le redémarrage de l'application.

### Docker

Docker lance le projet dans plusieurs conteneurs : PHP-FPM exécute Symfony, Nginx reçoit les requêtes, MySQL stocke les données et phpMyAdmin permet de les consulter.

> Docker me permet d'avoir le même environnement et les mêmes versions sur chaque machine.

Mots-clés : `conteneur`, `image`, `volume`, `réseau`, `port`, `docker compose`.

### Stripe

Stripe Checkout affiche une page de paiement hébergée par Stripe. Symfony prépare les produits, les prix et les URL de retour.

> Les données bancaires ne passent pas dans mon formulaire : le client est redirigé vers la page sécurisée de Stripe.

### EasyAdmin

EasyAdmin génère une interface CRUD pour consulter, créer, modifier et supprimer les données de la boutique.

> EasyAdmin m'évite de développer manuellement chaque écran d'administration.

Mots-clés : `CRUD`, `Dashboard`, `Field`, `AssociationField`.

## 5. Controllers publics

### AccueilController

- Sert à afficher l'accueil.
- Utilisé pour `/` et `/accueil`.
- La racine `/` redirige vers la route `app_accueil`.

**Ce que je peux dire à l'oral :**

> Ce Controller gère la page d'accueil. J'ai aussi ajouté une redirection depuis la racine du site pour éviter une page introuvable.

Mots-clés : `Route`, `redirectToRoute`, `render`.

### CatalogueController

- Sert à afficher les figurines disponibles.
- Utilisé quand le client ouvre `/catalogue`.
- Il appelle `FigurineRepository->findAll()` puis envoie le résultat à Twig.

**Ce que je peux dire à l'oral :**

> Ce Controller récupère les figurines présentes dans la base grâce au Repository, puis les transmet à la vue Twig pour construire le catalogue.

Mots-clés : `Repository`, `findAll`, `tableau d'objets`, `Twig`.

### PersonnalisationController

- Sert à ouvrir la personnalisation d'une figurine.
- Utilisé pour `/personnalisation/{id}`.
- Il recherche la figurine par son identifiant et renvoie une erreur 404 si elle n'existe pas.

**Ce que je peux dire à l'oral :**

> Ce Controller récupère l'identifiant dans l'URL, recherche la figurine en base et l'envoie à la page de personnalisation. Il vérifie aussi que la figurine existe.

Mots-clés : `paramètre de route`, `find`, `id`, `404`.

### PanierController

- Sert à afficher, ajouter et supprimer les articles du panier.
- Il lance également le paiement Stripe.
- Le panier est un tableau conservé dans la session du visiteur.

**Ce que je peux dire à l'oral :**

> Ce Controller centralise le panier. Il récupère la session, ajoute ou supprime des articles et prépare les lignes envoyées à Stripe Checkout.

Mots-clés : `session`, `panier`, `Request`, `redirect`, `Stripe Checkout`.

### InscriptionController

- Sert à créer un compte utilisateur.
- Utilisé lors d'un envoi POST sur `/inscription`.
- Il récupère les champs, hache le mot de passe puis utilise `persist()` et `flush()`.

**Ce que je peux dire à l'oral :**

> Ce Controller traite le formulaire d'inscription. Le mot de passe n'est jamais enregistré en clair : il est haché avant la persistance en base.

Mots-clés : `POST`, `hashPassword`, `EntityManager`, `persist`, `flush`.

### SecurityController

- Sert à afficher la connexion et à déclarer la route de déconnexion.
- Symfony réalise réellement la vérification grâce à `security.yaml`.
- Il transmet le dernier email et l'éventuelle erreur à Twig.

**Ce que je peux dire à l'oral :**

> Ce Controller affiche le formulaire de connexion. La vérification du mot de passe et la déconnexion sont prises en charge par le composant Security de Symfony.

Mots-clés : `AuthenticationUtils`, `firewall`, `provider`, `CSRF`, `logout`.

### AproposController

- Sert à afficher la présentation du projet.
- Utilisé pour `/apropos`.
- Il ne consulte pas la base de données.

**Ce que je peux dire à l'oral :**

> Ce Controller est simple : il retourne uniquement le template de la page À propos.

Mots-clés : `Route`, `render`, `page statique`.

### ProduitController

- Il correspond encore à une page d'exemple générée par Symfony.
- Il est accessible par `/produit` mais ne participe pas au parcours principal.

**Ce que je peux dire à l'oral :**

> Ce fichier est un reste du démarrage du projet. Le vrai affichage des produits est géré par le CatalogueController.

## 6. Controllers EasyAdmin

### DashboardController

- Configure le titre et le menu de l'administration.
- Redirige l'entrée `/admin` vers la liste des commandes.

> Ce Controller construit le tableau de bord EasyAdmin et donne accès aux différentes données de la boutique.

### FigurineCrudController

- Associe EasyAdmin à l'Entity `Figurine`.
- Configure les champs nom, prix, description et image.

> Il génère les écrans permettant de gérer les figurines dans l'administration.

### PersonnalisationCrudController

- Configure les champs d'une personnalisation.
- Utilise un `AssociationField` pour choisir la figurine liée.

> Il permet à l'administrateur de gérer les options et leur relation avec une figurine.

### CommandeCrudController

- Configure la date, le statut, l'utilisateur et la personnalisation.
- Affiche aussi les paiements liés.

> Il permet de consulter et modifier les commandes depuis EasyAdmin.

### PaiementCrudController

- Configure le montant, le mode de paiement et la commande liée.

> Il représente la partie administrative des paiements enregistrés en base.

### UtilisateurCrudController

- Configure le nom, le prénom, l'email et les rôles.

> Il permet d'administrer les comptes utilisateurs et leurs rôles.

## 7. Entités Doctrine

### Figurine

Table principale du catalogue : `id`, `nom`, `prix_base`, `description`, `image`.
Une figurine possède plusieurs personnalisations avec une relation `OneToMany`.
Elle est utilisée lors de l'affichage du catalogue et de la personnalisation.

> Cette Entity représente un produit de base vendu dans la boutique.

Mots-clés : `Entity`, `OneToMany`, `prix de base`.

### Personnalisation

Contient un nom, une image, un prix et une figurine liée.
Plusieurs personnalisations peuvent appartenir à la même figurine : `ManyToOne`.
Elle est utilisée pour représenter les choix associés aux produits.

> Cette Entity représente une option payante associée à une figurine.

Mots-clés : `ManyToOne`, `clé étrangère`, `option`.

### Utilisateur

Contient l'identité, l'email, le mot de passe haché et les rôles.
Elle implémente les interfaces demandées par Symfony Security.
Elle est utilisée pendant l'inscription, la connexion et la création d'une commande.

> Cette Entity représente un compte capable de se connecter au site.

Mots-clés : `UserInterface`, `email unique`, `roles`, `mot de passe haché`.

### Commande

Contient une date, un statut, un utilisateur, une personnalisation et des paiements.
Elle est surtout utilisée dans EasyAdmin pour le moment.

> Cette Entity représente l'achat métier et relie le client à son choix de personnalisation.

Mots-clés : `ManyToOne`, `statut`, `relation utilisateur`.

### Paiement

Contient un montant, un mode de paiement et une commande liée.
Elle est surtout utilisée dans EasyAdmin pour le moment.

> Cette Entity permet de rattacher une information de paiement à une commande.

Mots-clés : `montant`, `mode de paiement`, `commande liée`.

## 8. Repositories

- `FigurineRepository` : accès aux figurines.
- `PersonnalisationRepository` : accès aux personnalisations.
- `UtilisateurRepository` : recherche des utilisateurs, notamment par email pour la connexion.
- `CommandeRepository` : accès aux commandes.
- `PaiementRepository` : accès aux paiements.

Les méthodes standards disponibles sont notamment `find()`, `findAll()`, `findBy()` et `findOneBy()`.

> Un Repository regroupe les recherches liées à une Entity. Pour le catalogue, j'utilise `FigurineRepository` afin de ne pas écrire la requête directement dans le Controller.

Ils sont utilisés lorsqu'un Controller ou Symfony Security doit lire une donnée en base.

Mots-clés : `ServiceEntityRepository`, `find`, `findAll`, `requête Doctrine`.

### UtilisateursRepository

Ce fichier est un ancien doublon au pluriel. Il référence `App\Entity\Utilisateurs`, qui n'existe pas. Il n'est pas utilisé dans le parcours du site et ne doit pas être confondu avec `UtilisateurRepository`.

## 9. Templates Twig

### base.html.twig

Structure commune : `<head>`, header, navigation, recherche, footer, popup de connexion et menu burger.
Les autres templates l'utilisent avec `{% extends 'base.html.twig' %}`.

> C'est le squelette commun qui évite de répéter le header et le footer sur chaque page.

Mots-clés : `extends`, `block`, `app.user`, `csrf_token`.

### accueil/index.html.twig

Affiche le hero, la présentation du projet, le bouton catalogue et les images décoratives.

> Ce template contient la partie visuelle de la page d'accueil.

### catalogue/index.html.twig

Boucle sur `figurines`, affiche chaque carte et ouvre la description avec JavaScript.

> Ce template reçoit les figurines du Controller et utilise une boucle Twig pour générer les cartes.

Mots-clés : `for`, `figurine`, `asset`, `path`.

### personnalisation/index.html.twig

Affiche les catégories, l'aperçu et le récapitulatif. Le JavaScript change l'image, le prix et le lien d'ajout au panier.

> Ce template mélange Twig pour la figurine de départ et JavaScript pour les changements visibles sans rechargement.

Mots-clés : `DOM`, `JavaScript`, `encodeURIComponent`, `prix de base`.

### panier/index.html.twig

Affiche les articles de la session et calcule le total général avec Twig. Le bouton appelle la route Stripe.

> Ce template parcourt le panier, calcule chaque ligne puis affiche le total et le bouton de paiement.

Mots-clés : `session`, `for`, `set`, `total`.

### inscription/index.html.twig

Formulaire HTML envoyé en POST à `InscriptionController`.

> Ce template collecte les informations nécessaires pour créer un compte.

Mots-clés : `form`, `POST`, `required`.

### security/index.html.twig

Formulaire de connexion avec email, mot de passe et jeton CSRF.

> Ce template envoie les identifiants au système de sécurité Symfony.

Mots-clés : `CSRF`, `last_username`, `error`.

### panier/success.html.twig et panier/cancel.html.twig

Affichent le résultat du retour Stripe. Le succès annonce que le panier est vidé ; l'annulation conserve le panier.

> Ces templates donnent un retour clair après le passage sur Stripe.

### apropos/index.html.twig

Présente le projet et son objectif.

> Cette vue est statique et n'a pas besoin de données venant de MySQL.

### produit/index.html.twig

Template d'exemple Symfony encore présent mais non utilisé par le parcours e-commerce principal.

> Je ne le présente pas comme une fonctionnalité terminée : c'est un fichier de démonstration restant.

## 10. Panier et personnalisation

Le panier n'est pas encore une table. Il est stocké dans la session sous la clé `panier`.
Chaque ligne contient le nom, la description, l'image, la personnalisation, le prix et la quantité.

La personnalisation visuelle est actuellement pilotée par JavaScript. Les options visibles dans Twig sont écrites directement dans le template.

> J'ai choisi la session pour réaliser un panier simple sans obliger le visiteur à être connecté. Pour une version plus avancée, je pourrais enregistrer le panier en base.

### Comprendre le code de personnalisation et les images

La page reçoit d'abord la figurine choisie depuis `PersonnalisationController` :

```twig
<img
    id="figurine-preview"
    src="{{ asset('images/' ~ figurine.image) }}"
    class="figurine-img"
    alt="{{ figurine.nom }}"
>
```

- `figurine.image` vient de la base de données.
- `asset('images/...')` construit le chemin vers `public/images`.
- `id="figurine-preview"` permet à JavaScript de remplacer l'image affichée.

> Au chargement, Twig affiche l'image de base enregistrée dans l'Entity Figurine.

Les petites images comme `Hair1.png`, `costume1.png` ou `chapeau1.png` sont les miniatures proposées au client.
Quand il clique sur `Ajouter`, le bouton appelle `changeFigurine()` :

```twig
onclick="changeFigurine(
    'figurine-moderne-classique1.png',
    'Cheveux Moderne 1',
    20
)"
```

Les trois paramètres sont :

1. L'image finale à afficher.
2. Le nom de la personnalisation.
3. Le supplément ajouté au prix de base.

Les images utilisées sont déjà préparées :

- `Hair1.png` à `Hair4.png` : miniatures des coiffures.
- `figurine-moderne-classique1.png` à `figurine-moderne-classique4.png` : aperçus finaux avec les coiffures.
- `costume1.png`, `Shogun2.png`, `imperiale3.png` : miniatures des vêtements.
- `Figurine_costume1.png`, `figurine-shogun-elegant.png`, `figurine-samourai-imperial.png` : aperçus finaux des costumes.
- `chapeau1.png` : miniature de l'accessoire.
- `figurine_chapeau1.png` : aperçu final avec le chapeau.

Le configurateur ne fusionne donc pas plusieurs images. Il remplace l'image centrale par une autre image complète déjà présente dans `public/images`.

La variable suivante récupère le prix de base envoyé par Twig :

```javascript
const basePrice = {{ figurine.prixBase }};
```

Puis la fonction calcule et affiche le nouveau prix :

```javascript
const total = basePrice + extraPrice;

document.getElementById('figurine-preview').src = '/images/' + image;
document.getElementById('custom-name').innerText = customName;
document.getElementById('custom-price').innerText = extraPrice + ' €';
document.getElementById('total-price').innerText = total + ' €';
```

- `getElementById()` sélectionne un élément HTML.
- `.src` remplace l'image centrale.
- `.innerText` remplace les textes du récapitulatif.
- Le navigateur met à jour la page sans rechargement.

La fonction modifie enfin le lien du bouton panier :

```javascript
document.getElementById('btn-panier').href =
    '/panier/add/{{ figurine.id }}'
    + '?image=' + encodeURIComponent(image)
    + '&custom=' + encodeURIComponent(customName)
    + '&prix=' + total;
```

Le lien transmet au `PanierController` :

- l'identifiant de la figurine dans l'URL ;
- le nom de l'image avec `image` ;
- le choix avec `custom` ;
- le prix final avec `prix`.

`encodeURIComponent()` protège les espaces et les caractères spéciaux dans l'URL.

Les onglets appellent `showCategory()` :

```javascript
function showCategory(category, button)
{
    document.querySelectorAll('.category').forEach(cat => {
        cat.classList.add('hidden');
    });

    document.getElementById(category).classList.remove('hidden');
}
```

La fonction cache toutes les catégories puis affiche seulement celle qui a été choisie : cheveux, vêtements ou accessoires.

Les flèches placées autour de la figurine sont actuellement visuelles. Aucun événement JavaScript ne leur permet encore de changer d'option.

### Ce que je peux dire à l'oral

> La figurine de départ vient de MySQL et son image est affichée par Twig. Les options sont organisées en catégories dans le template. Quand le client clique sur une option, une fonction JavaScript remplace l'aperçu par une image finale déjà préparée, calcule le supplément et met à jour le lien vers le panier. La page n'a pas besoin d'être rechargée.

Mots-clés : `asset`, `DOM`, `getElementById`, `onclick`, `innerText`, `src`, `encodeURIComponent`, `prix de base`, `supplément`.

## 11. Paiement Stripe

1. Le package `stripe/stripe-php` est installé avec Composer.
2. La clé secrète est lue dans `STRIPE_SECRET_KEY`.
3. Le Controller transforme le panier en `line_items`.
4. Les prix sont multipliés par 100 car Stripe travaille en centimes.
5. `Session::create()` crée le Checkout.
6. Symfony redirige vers l'URL donnée par Stripe.

La clé `pk_test_...` est publique. La clé `sk_test_...` est secrète et doit rester côté serveur dans `.env.local`.
La vraie clé secrète ne doit jamais être commitée ni montrée pendant l'oral.
Dans l'état actuel du projet, des clés de test sont encore présentes dans `.env` : il faut les régénérer dans Stripe et les déplacer dans `.env.local` avant de partager le dépôt.

## 12. Authentification

`security.yaml` indique que les utilisateurs sont chargés depuis l'Entity `Utilisateur` grâce à leur email.
Le mot de passe est haché à l'inscription et comparé automatiquement à la connexion.
Le jeton CSRF protège le formulaire de connexion contre l'envoi d'une requête forgée.

> Symfony Security gère l'authentification à partir de l'email, du mot de passe haché, du provider Doctrine et du firewall principal.

## 13. Points honnêtes à connaître

Ces points sont des améliorations possibles, pas des fonctions déjà terminées :

- Le prix personnalisé arrive actuellement dans l'URL et devrait être recalculé côté serveur.
- Le retour Stripe ne vérifie pas encore un webhook.
- Le succès Stripe vide le panier mais ne crée pas automatiquement une `Commande` et un `Paiement` en base.
- La route `/admin` n'est pas encore limitée à `ROLE_ADMIN` dans `security.yaml`.
- Le formulaire d'inscription devrait utiliser Symfony Forms, des contraintes de validation et un jeton CSRF.
- La barre de recherche est seulement visuelle.
- Les flèches de personnalisation n'ont pas encore de traitement JavaScript.
- `ProduitController` et `UtilisateursRepository` sont des restes à nettoyer.

### Réponse possible au jury

> Mon projet est une première version fonctionnelle. J'ai terminé le parcours catalogue, personnalisation, panier et paiement de test. Pour une mise en production, mes priorités seraient de sécuriser les prix côté serveur, ajouter un webhook Stripe, protéger EasyAdmin avec un rôle administrateur et enregistrer automatiquement les commandes après paiement.

## 14. Questions rapides du jury

**Pourquoi utiliser un Repository ?**

Pour regrouper les recherches en base et éviter de mettre des requêtes dans les Controllers.

**Pourquoi utiliser Twig ?**

Pour séparer le HTML de la logique PHP et afficher proprement les données envoyées par Symfony.

**Pourquoi hacher le mot de passe ?**

Pour ne jamais conserver le mot de passe réel dans la base.

**Pourquoi multiplier le prix par 100 pour Stripe ?**

Parce que Stripe attend le montant dans la plus petite unité de la monnaie, donc en centimes pour l'euro.

**Pourquoi utiliser une session pour le panier ?**

Pour conserver le panier d'un visiteur entre plusieurs pages sans l'enregistrer immédiatement en base.

**Quelle différence entre `persist()` et `flush()` ?**

`persist()` prépare l'objet pour Doctrine. `flush()` exécute réellement l'écriture en base.

**Quelle différence entre une Entity et un Repository ?**

L'Entity décrit les données. Le Repository sert à les rechercher.

**Qu'est-ce qu'une migration ?**

Un fichier versionné qui applique une modification de structure à la base de données.

## 15. Démonstration conseillée

1. Présenter rapidement l'accueil et le responsive.
2. Ouvrir le catalogue et montrer que les données viennent de MySQL.
3. Choisir une figurine et modifier sa personnalisation.
4. Ajouter au panier et expliquer la session.
5. Lancer Stripe avec une carte de test.
6. Montrer EasyAdmin et les relations entre les données.
7. Terminer par une amélioration prévue plutôt que cacher les limites.
