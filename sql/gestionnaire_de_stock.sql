CREATE DATABASE IF NOT EXISTS gestionnaire_de_stock_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE gestionnaire_de_stock_db;

-- ===============================================
-- table 1:users
-- stock les comptes admin et gestionnaires
-- ===============================================
CREATE TABLE users  (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('superadmin','gestionnaire') NOT NULL,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- table 2: categries
-- permet de classer les articles
-- =============================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR (100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- table 3: fournisseurs
-- stocke  les fournisseurs de l'entreprise
-- =============================================
CREATE TABLE fournisseurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    telephone VARCHAR(20),
    email VARCHAR(150),
    addresse TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- table 4: articles
-- catalogue complet des pproduits
-- =================================================
CREATE TABLE articles(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix_unitaire DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    quantite INT NOT NULL DEFAULT 0,
    seuil_min INT NOT NULL DEFAULT 5,
    image VARCHAR(255) DEFAULT NULL,
    id_categorie INT DEFAULT NULL,
    id_fournisseur INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (id_fournisseur) REFERENCES fournisseurs(id) ON DELETE SET NULL
);

-- ============================================
-- TABLE 5 : MOUVEMENTS
-- historique de toutes les entrees et sorties
-- ==========================================--------
CREATE TABLE mouvements(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_article INT NOT NULL,
    type ENUM('entrer', 'sortie') NOT NULL,
    quantite INT NOT NULL,
    motif VARCHAR(255),
    id_user INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_article) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE RESTRICT
);
