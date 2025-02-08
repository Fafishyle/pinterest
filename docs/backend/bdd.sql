-- Structure de la table users
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  pseudo VARCHAR(250) NOT NULL,
  email VARCHAR(250) NOT NULL,
  password VARCHAR(250) NOT NULL,
  admin BOOLEAN DEFAULT FALSE
);

-- Structure de la table `categorie`
CREATE TABLE categorie (
  catId int NOT NULL,
  nomCat varchar(250) DEFAULT NULL
);

-- Déchargement des données de la table `categorie`
INSERT INTO categorie (catId, nomCat) VALUES
(1, 'animaux'),
(2, 'vetements'),
(3, 'voitures'),
(4, 'nourriture');

-- Structure de la table `photo`
CREATE TABLE photo (
  photoId int NOT NULL,
  nomFich varchar(250) NOT NULL,
  description varchar(250) NOT NULL,
  catId int NOT NULL
);

-- Déchargement des données de la table `photo`
INSERT INTO photo (photoId, nomFich, description, catId) VALUES
(1, 'aubergine.jpg', 'Des aubergines grillées dans une assiette', 4),
(2, 'burger-story.jpg', 'Hamburger americain classique sur une table en bois près donion rings', 4),
(3, 'ecureil.jpeg', 'Un écureuil dans la nature avec une noix.', 1),
(4, 'oiseau.jpg', 'rouge-gorge, oiseau au plumage orange, blanc et bleu', 1),
(5, 'pancake.jpg', 'Plusieurs pancakes décorées avec des fruits rouges', 4),
(6, 'pantalon-fluide.jpeg', 'pantalon leger de femme très coloré, un sac en rafia et des sandalettes', 2),
(7, 'pull.jpg', 'Femme portant un pull rose aux manches longues et col roulé.', 2),
(8, 'salade-carrefrais.jpg', 'salade vue du haut dans un bol sur une table', 4),
(9, 'short.jpg', 'Short en jean de couleur bleu', 2),
(10, 't_shirt.png', 't-shirt blanc simple avec écrit dessus \"I want a ticket to anywhere\", des lunettes de soleil et un jean', 2),
(11, 'tigre.jpg', 'Tigre dans la nature allongé sur le sol', 1),
(12, 'voit_gris.jpg', 'une Range rover grise sur une route avec un fond floue', 3),
(13, 'voit_jaune.jpg', 'une petite voiture jaune garée devant un batiment en plein après-midi', 3),
(14, 'voit_orange.jpg', 'une Volkswagen Coccinelle orange vu de profil, garée devant un immeuble', 3),
(15, 'voit_rouge.jpg', 'une Tesla rouge vue de 3/4', 3);