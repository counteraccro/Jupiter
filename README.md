#Projet Jupiter

Le projet Jupiter est un simulateur de Battle Royale multijoueur ou chaque action est définie de façon aléatoire mais cohérente.

Le projet est actuellement en version 1.0

###Comment jouer
Lancer le projet > Définissez le nom de votre joueur > Sélection automatique du lobby et attente d'autres joueurs > Lancement de la partie > Regarder le résultat

###Installation du projet

**Le projet tourne avec les versions suivantes :**
* Version de PHP : 7.0.10
* Mysql : 5.7.14
* Apache : 2.4.23

**Librairies intégrées**
* Jquery 3.2.1
* Materialize v0.100.2

**Installation**

Étape 1 : cloner le dépôt GIT

`https://github.com/counteraccro/Jupiter.git`

Étape 2 : Installer Symfony

`composer symfony`

Étape 3 : installer la base de données

`php bin/console doctrine:database:create`

Étape 4: installation des fixtures

`php bin/console doctrine:fixture:load`

Étape 5: accès au projet via l'url 

`http://localhost/jupiter/web/app_dev.php/`

###Personnalisation
Il est possible de personnaliser certaines éléments du projet

####Le nom des bots
À la création d'une partie, le programme remplit automatiquement les places libres par des bots. Ces bots sont générés de façon automatique via une liste de noms définis.
Vous pouvez modifier cette liste qui se trouve ici `jupiter\src\AppBundle\Resources\data\first_name.yml`

####Les logs des actions
Chaque action est personnalisable dans le fichier `jupiter\src\AppBundle\Resources\data\logs.yml`

####La probabilitée d'apparition de chaque action
Vous pouvez influencer sur la probabilité d'apparition des actions dans le fichier `jupiter\src\AppBundle\Resources\data\random_actions_conditions.yml`

####Le nombre maximum de joueur dans une partie
Vous pouvez modifier le nombre de joueurs maximum dans une partie en modifiant la variable suivante : `$this->nbPlaceMax = 10;` dans le fichier
`jupiter\src\AppBundle\Entity\Lobby.php`

Par défaut, la valeur est à 10.

**Attention plus la valeur sera grande et plus le temps d'exécution du script sera long**

###Evolutions à venir
Voici la liste des améliorations/évolutions à venir
* Ajout d'une notion aléatoire de contre et d'échec sur l'action tuer
* Refonte des logs pour prendre en compte le joueur masculin et féminin
* Ajout de l'action suivre
* Ajout de l'action dons de sponsors
* Ajout de la notion d'usure des armes
* Ajout d'une personnalisation des joueurs
* Ajout d'un caractère aux IA
* Ajout de la notion de blessure