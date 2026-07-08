# Conducteur oral - parcours utilisateur

## Regle simple

Sur les slides du parcours, je raconte d'abord ce que fait l'utilisateur.
Ensuite seulement, je montre le code comme une preuve technique.

## Slide 8 - Parcours utilisateur

Phrase orale :

"Je vais presenter le projet en suivant le parcours reel d'un utilisateur. Le but est que le jury comprenne d'abord l'utilisation du site, puis je montre les parties techniques importantes au bon moment."

## Slide 9 - Inscription et connexion

Phrase orale :

"L'utilisateur commence par creer son compte. Ensuite je peux verifier dans la base de donnees que l'utilisateur existe bien avec le role ROLE_USER."

Quand montrer le code :

"Cote developpement, le controller recupere le formulaire, hash le mot de passe, puis Doctrine enregistre l'utilisateur avec persist et flush."

Phrase a eviter :

"Je ne reexplique pas toute la route ici, je me concentre sur ce que fait le controller."

## Slide 10 - Catalogue

Phrase orale :

"Une fois connecte, l'utilisateur arrive sur le catalogue et consulte les figurines disponibles."

Quand montrer le code :

"Techniquement, le controller appelle le repository. La methode findAll recupere les figurines, puis Twig les affiche sous forme de cartes produit."

## Slide 11 - Personnalisation

Phrase orale :

"L'utilisateur choisit une figurine, puis modifie ses options de personnalisation. Le prix et l'affichage changent selon ses choix."

Quand montrer le code :

"Ici je montre surtout l'interaction entre les donnees de la figurine, l'affichage Twig et le JavaScript qui met a jour la page."

## Slide 12 - Panier

Phrase orale :

"Quand l'utilisateur ajoute au panier, ses choix sont conserves en session. Il peut modifier ou supprimer ses articles avant de commander."

Quand montrer le code :

"Le panier n'est pas juste une page visuelle : il calcule les lignes, les quantites et le total general."

## Slide 13 - Paiement Stripe

Phrase orale :

"Pour le paiement, le site cree une session Stripe et redirige l'utilisateur vers une page securisee. Les donnees bancaires ne sont pas stockees dans mon application."

Quand montrer le code :

"Je montre la creation de la session Stripe, avec le montant, l'URL de succes et l'URL d'annulation."

