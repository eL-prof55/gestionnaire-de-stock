  #  Gestionnaire-de-Stock
web application of stock management
Application web de gestion de stock
Projet académique — KEYCE Informatique & IA
PHP 8.1  ·  MySQL  ·  AJAX  ·  XML  ·  HTML5/CSS3/JS
 
   #   Fonctionnalités
•	Authentification sécurisée avec deux rôles (SuperAdmin / Gestionnaire)
•	Compte SuperAdmin unique — une fois créé, personne ne peut en recréer un autre
•	Gestion des catégories d'articles
•	Gestion complète des articles avec upload d'images
•	Gestion des fournisseurs
•	Suivi des entrées et sorties de stock en temps réel
•	Alertes automatiques lorsqu'un article passe sous le seuil minimum
•	Rapports et statistiques du stock
•	Export et import de données au format XML
•	Interface moderne avec animations et loading page au démarrage

  # Technologies utilisées
-----------------------------------------------------
Côté	                   |Technologies
-----------------------------------------------------
Backend	                |PHP 8.1 — architecture MVC
Base de données	        |MySQL / MariaDB
Interactions dynamiques	|AJAX via Fetch API JavaScript
Échange de données    	|XML (export et import)
Frontend	              |HTML5 · CSS3 · JavaScript
-------------------------------------------------------

  # Installation locale
Prérequis
•	XAMPP ou WAMP installé
•	PHP 8.1 ou supérieur
•	MySQL / MariaDB
•	Git installé et configuré

  #  Étapes
1. Cloner le repo
git clone https://github.com/TON_USERNAME/gestionnaire-de-stock.git
2. Placer le dossier
•	XAMPP  →  copier dans C:/xampp/htdocs/gestionnaire-de-stock
•	WAMP   →  copier dans C:/wamp64/www/gestionnaire-de-stock
3. Importer la base de données
1.	Démarrer Apache et MySQL dans XAMPP/WAMP
2.	Ouvrir phpMyAdmin → http://localhost/phpmyadmin
3.	Créer une base nommée gestionnaire_de_stock_db en utf8mb4_unicode_ci
4.	Cliquer sur Importer → sélectionner sql/gestionnaire_de_stock.sql
4. Configurer la connexion BDD
Copier config/db.example.php → config/db.php et remplir avec vos identifiants locaux.
5. Accéder à l'application
http://localhost/gestionnaire-de-stock/
6. Premier accès — Inscription SuperAdmin
   NB: La première page affichée est le formulaire d'inscription du SuperAdmin. Une fois ce compte créé, cette page est désactivée définitivement — même depuis un autre PC. La vérification se fait côté serveur en base de données.

#    Structure du projet
--------------------------------------------------------
Dossier	      |  Rôle
-------------------------------------------------------
config/    	  |  Connexion BDD et constantes globales
models/    	  |  Requêtes SQL — 1 fichier par table
controllers/	|  Logique métier — 1 fichier par module
views/	      |  Pages HTML/PHP affichées à l'utilisateur
ajax/	        |  Endpoints appelés par les requêtes AJAX
xml/	        |  Export et import de fichiers XML
assets/	      |  CSS, JS et images uploadées des produits
sql/	        |  Script SQL de création de la base de données
---------------------------------------------------------------

  # Base de données — 5 tables
--------------------------------------------------------------------------
Table          |  	Description
--------------------------------------------------------------------------
users          |	Comptes SuperAdmin et Gestionnaires
categories	   | Catégories des articles
fournisseurs   |Fournisseurs de l'entreprise
articles	     | Catalogue des produits (image, catégorie, fournisseur)
mouvements	   | Historique de toutes les entrées/sorties de stock
----------------------------------------------------------------------------

  # Équipe
-------------------------------------------------------------------
Membre	            |  Rôle
-------------------------------------------------------------------
MANGA ALIMA  — Lead	|  Auth + Config + Architecture + Dashboard
NOUKAM RIVALDO	    |  Articles + Catégories + Upload images
DOMCHE DANIELLE	    |  Fournisseurs + Mouvements de stock
MASSA MAEVA	        |  Rapports + Export XML + Alertes
-------------------------------------------------------------------

   Avancement du projet
	Phase	Contenu
	Phase 1	Structure & Base de données
	Phase 2	Authentification
	Phase 3	Articles + Catégories
	Phase 4	Fournisseurs
	Phase 5	Mouvements de stock
	Phase 6	Alertes stock minimum
	Phase 7	Rapports & Export/Import XML
	Phase 8	UI finale & Tests

Projet académique — KEYCE Informatique & IA — Tous droits réservés
