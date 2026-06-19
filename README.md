# 🗾⚔️ Kasutamu Pon

## 📖 Présentation du projet

Kasutamu Pon est une application web de personnalisation et de vente de figurines inspirées de l'univers manga et de la culture japonaise.

L'objectif du projet est de permettre aux utilisateurs de créer leur propre figurine personnalisée en sélectionnant différents éléments visuels avant de passer commande.

Ce projet a été réalisé dans le cadre du Titre Professionnel Développeur Web et Web Mobile (DWWM).

---

## 🎯 Objectifs du projet

L'application permet de :

- Consulter un catalogue de figurines
- Personnaliser une figurine
- Ajouter une figurine au panier
- Gérer une commande
- Effectuer un paiement sécurisé
- Accéder à un espace administrateur
- Gérer le catalogue et les commandes

---

## 🛠️ Technologies utilisées

### Front-end

- HTML5
- CSS3
- JavaScript
- Twig

### Back-end

- PHP 8.2
- Symfony 7
- Doctrine ORM

### Base de données

- MySQL 8

### Environnement

- Docker
- Docker Compose

### Outils

- Visual Studio Code
- Git / GitHub
- Figma
- Trello

---

## 🏗️ Architecture du projet

Le projet repose sur une architecture MVC (Modèle - Vue - Contrôleur).

### Modèle

Les entités représentent les données métier :

- Utilisateur
- Figurine
- Personnalisation
- Commande
- Paiement

### Vue

Les vues sont développées avec Twig afin de générer les interfaces utilisateurs.

### Contrôleur

Les contrôleurs assurent :

- Le traitement des requêtes
- La gestion des données
- La communication entre les vues et la base de données

---

## 🔐 Sécurité

### Gestion des mots de passe

Les mots de passe ne sont jamais stockés en clair dans la base de données.

Symfony applique un système de hachage sécurisé avant leur enregistrement afin de protéger les comptes utilisateurs.

### Gestion des accès

L'espace d'administration est protégé par un système d'authentification.

Seuls les utilisateurs autorisés peuvent :

- Ajouter des figurines
- Modifier le catalogue
- Consulter les commandes
- Gérer les utilisateurs

---

## 📱 Responsive Design

Le site a été développé afin d'être compatible avec :

- 📱 Smartphone
- 📲 Tablette
- 💻 Ordinateur

L'interface s'adapte automatiquement à la taille de l'écran.

---

## 📦 Installation du projet

### 1. Cloner le projet

```bash
git clone https://github.com/ryan95380/Kasutamu_Pon.git

cd Kasutamu_Pon
```

### 2. Démarrer Docker

```bash
docker compose up -d --build
```

Vérifier les conteneurs :

```bash
docker ps
```

### 3. Installer les dépendances Symfony

```bash
docker compose exec php composer install
```

### 4. Vérifier la configuration

Dans le fichier `.env` :

```env
DATABASE_URL="mysql://app:app@db:3306/Kasutamu_Pon?serverVersion=8.0"
```

### 5. Créer la base de données

```bash
docker compose exec php php bin/console doctrine:database:create
```

### 6. Exécuter les migrations

```bash
docker compose exec php php bin/console doctrine:migrations:migrate
```

### 7. Vider le cache

```bash
docker compose exec php php bin/console cache:clear
```

---

## ▶️ Accès à l'application

### Site web

```text
http://localhost:8085
```

### phpMyAdmin

```text
http://localhost:8082
```

Connexion :

```text
Serveur : db
Utilisateur : app
Mot de passe : app
```

---

## 📂 Structure du projet

```text
src/
├── Controller/
├── Entity/
├── Repository/
├── Form/

templates/
├── accueil/
├── catalogue/
├── personnalisation/
├── panier/
├── admin/

public/
├── css/
├── js/
├── images/

config/
docker/
migrations/
```

---

## ⚙️ Commandes utiles

### Démarrer le projet

```bash
docker compose up -d
```

### Arrêter le projet

```bash
docker compose down
```

### Reconstruire complètement

```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

### Vider le cache Symfony

```bash
docker compose exec php php bin/console cache:clear
```

---

## 🚀 Fonctionnalités

### Utilisateur

- Consultation du catalogue
- Personnalisation des figurines
- Ajout au panier
- Validation des commandes
- Gestion du compte utilisateur

### Administrateur

- Gestion du catalogue
- Gestion des figurines
- Gestion des commandes
- Gestion des utilisateurs
- Gestion des paiements

---

## 📈 Évolutions possibles

- Intégration complète de Stripe
- Gestion des stocks
- Historique des commandes
- Système d'avis clients
- Gestion avancée des rôles
- Tableau de bord statistiques

---

## 👨‍💻 Auteur

Ryan Mambou

Projet réalisé dans le cadre du Titre Professionnel Développeur Web et Web Mobile (DWWM).

Année 2025 - 2026
