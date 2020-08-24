# Stage front-end été 2020

## Installation

S'assurer que Node est installé.

Installer les dépendances requises :

```bash
npm install
```

Lancer la commande suivante pour build webpack une première fois

```bash
npm run build
```

Rentrer la commande `npm start` et lancer un serveur local pour démarrer le projet. Le script utilise le symfony cli, mais il peut être modifié suivant vos besoins.

Lancer également un serveur local au niveau du projet qui contient l’API (addresse utilisée dans le projet: https://127.0.0.1:8001/)

## Dépendances

Le projet utilise React, Redux, React-Redux, React-Table, Webpack Encore, Bootstrap 4, Sass, axios pour les appels api et FontAwesome-React pour les icônes.

## Description

Le code react et sass se trouvent dans le folder _assets_ à la racine.

### Structure du dossier JS

_Index.js_ est l’entrypoint pour webpack à partir duquel le projet se build.

Tous les composant se trouvent dans le dossier _components_.

_App.js_ est le composant parent principal, où se définissent les routes. J’y ai rajouté un préfixe pour la route pour faire fonctionner react par-dessus le projet existant. La route dans symfony est définie par le contrôleur _FrontendController_.

Les composants liés au dashboard se trouvent dans le dossier _dashboard_, les composants liés à la homepage du site vitrine se trouvent dans le dossier _homepage_. Les composants plus indépendants (par exemple, le bouton) se trouvent à la racine du dossier _components_.

Pour Redux, les action creators sont dans le dossier _actions_ et les reducers dans le dossier _reducers_.

Les fonctions helpers sont dans le dossier _helpers_.
Le dossier _apis_ contient la mise en place de axios.

Exemple: hiérarchie des composants de la page “balance” du dashboard

- Balance.js (composant parent)
  - SearchForm
    - DateRange
    - Button
  - Movements (fait appel à l’action fetchMovements de Redux)
    - MovementCard
    - MovementsTable
      - TableTemplate
  - ExcelExport
  - Sidebar

### Structure du dossier CSS

index.scss est l’entrypoint pour que webpack build le scss en css.

Le dossier _abstracts_ contient le layout général (le grid container réutilisé à chaque page), le style lié à la typographie (textes, titres etc) et les variables sass.

Sous le dossier _components_ on retrouve une structure semblable à celle du dossier js. Le style des composants réutilisables se trouvent à la racine, ceux liés au dashboard dans le dossier _dashboard_, et ceux de la homepage dans le dossier _homepage_.

## To do

Au niveau de la homepage du site vitrine, il faut achever le formulaire de contact ainsi que la bannière réseaux sociaux. Il faut encore ajouter le footer, et les pages suivantes : Consulting Services, Payroll Services, Simulation et notice GDPR comme présenté dans les maquettes.

Au niveau du dashboard, il faut créer d'autres apis pour gérer l'identification des users, la récupération des invoices et la liste des contrats. Il faut encore créer des tableaux spécifiques aux invoices et aux contrats (à l'aide du composant TableTemplate), ainsi qu'une section "My Profile" reprenant certaines informations personnelles modifiables.
