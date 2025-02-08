-- Structure de la table users
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  pseudo VARCHAR(250) NOT NULL,
  email VARCHAR(250) NOT NULL,
  password VARCHAR(250) NOT NULL,
  admin BOOLEAN DEFAULT FALSE
);
