# Journal de bord en PHP

### Introduction

Ce projet centralise l'affichage d'une base de données d'articles hébergés sur phpMyAdmin.

Il permet aussi l'accès à une interface Administrateur qui permet de gérer ces articles comme suit :

![image](https://user-images.githubusercontent.com/74963340/219958599-8c076201-8ef8-42fa-9ef7-d35821a270b0.png)

### Lien

Vous pouvez retrouver ce site à l'adresse suivante https://web.sunil.fr/journal/

### Installation

Voici les étapes qui vous permettront d'installer et utiliser ce projet chez vous.

- Téléchargez les fichiers PHP dans un dossier de votre machine
- Hébergez ces fichiers sur votre hébergeur en ligne ou local
- Ouvrir le lien correspondant (`https://votre_url/journal/` ou bien `http://localhost/journal`)

### Usage

##

Une fois sur le site, vous arriverez sur la page publique dans laquelle vous pouvez :

- Lire tous les articles de la base en français ou en anglais par simple clic sur les boutons correspondants
- Changer le mode d'affichage (thème clair ou thème sombre). Ce thème sera enregistré sur votre ordinateur en localStorage
- Accéder au site administrateur

##

Une fois arrivé sur le site administrateur, vous pourrez :

- Créer un article à l'aide du bouton `+`
    - Vous arriverez sur une page dans laquelle vous pourrez remplir les champs `titre` et `texte`
    - Pour enregistrer l'article, cliquez sur le bouton Sauvegarder (en vert)
    - Pour retourner sur le menu, cliquez sur le bouton Retour (en rouge)

###

- Éditer un article à l'aide du bouton bleu avec l'icône `crayon`
    - Vous arriverez sur une page dans laquelle vous pourrez modifier les champs `titre` et `texte` préremplis avec les anciennes versions
    - Vous avez ensuite la possibilité de sauvegarder votre article en cliquant sur le bouton Sauvegarder (en vert)
    - Vous pouvez aussi traduire votre texte en anglais lorsque vous pensez avoir terminé toutes vos modifications via le bouton Traduire (en bleu)
    - Pour retourner sur le menu, cliquez sur le bouton Retour (en rouge)

###

- Supprimer un article à l'aide du bouton rouge avec l'icône `corbeille`

###

- Afficher votre article en français et anglais à l'aide du bouton violet avec l'icône `oeil`

###

