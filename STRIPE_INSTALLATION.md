# Installation Stripe en mode test

Le paiement du panier utilise Stripe Checkout.

1. Creer un compte Stripe ou se connecter au tableau de bord Stripe.
2. Activer le mode test.
3. Copier les cles API de test dans `.env.local` :

```dotenv
STRIPE_PUBLIC_KEY=pk_test_xxxxxxxxxxxxx
STRIPE_SECRET_KEY=sk_test_xxxxxxxxxxxxx
```

4. Relancer Symfony si necessaire.
5. Depuis le panier, cliquer sur `Passer au paiement`.

Carte de test Stripe :

```text
4242 4242 4242 4242
Date future
CVC au choix
```

Si `STRIPE_SECRET_KEY` est vide, le site affiche un message propre dans le panier au lieu de provoquer une erreur Symfony.
