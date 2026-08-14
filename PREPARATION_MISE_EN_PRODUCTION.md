# T-Pulse Archery - préparation de la mise en production

Dernière mise à jour : 14 août 2026.

Ce document centralise tout ce qui doit être confirmé par Tony avant d'accepter une vraie commande. Les occurrences `[À COMPLÉTER : ...]` sont également visibles sur les pages de préproduction concernées.

## Bloquants avant ouverture des ventes

- [ ] Créer ou confirmer l'entreprise et renseigner son statut exact : micro-entreprise/EI ou société.
- [ ] Renseigner le nom légal, la forme juridique, l'adresse professionnelle, le SIREN/SIRET, le registre éventuel, le téléphone et le directeur de publication.
- [ ] Confirmer le régime de TVA. En cas de franchise en base, utiliser la mention `TVA non applicable, art. 293 B du CGI` et vérifier la configuration fiscale WooCommerce.
- [ ] Ouvrir ou confirmer le compte bancaire dédié/professionnel et obtenir l'IBAN utilisé pour Stripe et la comptabilité.
- [ ] Créer le compte Stripe, terminer la vérification d'identité et connecter l'IBAN. Commencer en mode test.
- [ ] Créer le compte professionnel Mondial Relay, récupérer les identifiants techniques et choisir les zones desservies.
- [ ] Définir le délai de préparation, les tarifs d'expédition et l'éventuel seuil de livraison offerte.
- [ ] Respecter le minimum légal de frais d'envoi des livres neufs en France : vérifier la règle applicable au moment de l'ouverture, notamment pour une commande de livres inférieure à 35 euros.
- [ ] Choisir une adresse de retour et la reporter dans les CGV, la page Livraison et le formulaire de rétractation.
- [ ] Adhérer à un médiateur de la consommation puis publier son nom, son adresse et son site.
- [ ] Vérifier et compléter les pages Mentions légales, CGV, Confidentialité, Livraison et retours et Formulaire de rétractation. Une validation juridique professionnelle reste recommandée.
- [ ] Configurer l'envoi d'e-mails du domaine par SMTP et vérifier SPF, DKIM et DMARC.
- [ ] Configurer la facturation/comptabilité, par exemple Abby, ainsi que la numérotation continue des factures.
- [ ] Passer une commande complète en mode test : panier, code promo, adresse, livraison, paiement, e-mails, stock, remboursement et facture.

## Informations produits à confirmer

- [ ] HeliTwist : confirmer la formulation juridique autorisée pour le brevet. Indiquer si le brevet est délivré ou seulement déposé, avec le numéro publiable. Tant que ce point n'est pas confirmé, le site parle de conception T-Pulse sans revendiquer un brevet.
- [ ] HeliTwist : confirmer que le poids de 27 g est identique pour les trois filetages.
- [ ] HeliTwist : confirmer le contenu exact du colis et ajouter, si utile, une notice de montage/usage.
- [ ] HeliTwist : remplacer les stocks de démonstration de 10 unités pour chaque variante par les stocks réels `5/16`, `1/4` et `M8`.
- [ ] Livre : confirmer le prix public légal. Le site est à 15,00 euros alors qu'Amazon affichait 15,82 euros le 14 août 2026.
- [ ] Livre : renseigner l'ISBN, le poids d'un exemplaire et le stock réel.
- [ ] Livre : confirmer les caractéristiques reprises d'Amazon : broché, français, 79 pages, 14,81 x 21,01 cm.
- [ ] Avis Amazon : la fiche affiche uniquement la note globale 4,5/5 sur 21 évaluations, datée du 14 août 2026, avec un lien vers Amazon. Mettre à jour ce chiffre périodiquement. Ne pas recopier les textes individuels sans autorisation de leurs auteurs.

## Réglages WordPress et WooCommerce

- [ ] Supprimer ou remplacer l'article `Bienvenue dans l'univers T-Pulse Archery` lorsque la première vraie actualité est prête.
- [ ] Vérifier le stock faible, le seuil de rupture et les destinataires des alertes WooCommerce.
- [ ] Confirmer la vente avec ou sans compte client et vérifier les textes de Mon compte.
- [ ] Configurer les taxes après confirmation du régime de TVA.
- [ ] Configurer les zones et méthodes d'expédition. Aucune commande réelle ne doit être ouverte avant ce point.
- [ ] Installer/configurer Stripe en mode test, puis repasser en mode réel seulement après une commande test réussie.
- [ ] Ajouter PayPal uniquement si son coût et son intérêt commercial sont validés.
- [ ] Configurer Mondial Relay et vérifier la sélection du point relais sur ordinateur et mobile.
- [ ] Configurer le consentement cookies après activation des moyens de paiement, statistiques ou contenus tiers. Les boutons Accepter et Refuser doivent être proposés au même niveau.
- [ ] Configurer les sauvegardes hors hébergement, la sécurité et les mises à jour automatiques. Tester une restauration.
- [ ] Vérifier l'adresse e-mail qui reçoit les nouveaux avis et les avis en attente de modération.
- [ ] Supprimer les deux avis de test restants, le cas échéant, depuis Commentaires/Avis produit.

## E-mails et parcours client à vérifier

- [ ] Nouvelle commande reçue par l'administrateur.
- [ ] Commande en cours envoyée au client après paiement.
- [ ] Commande terminée envoyée au client lors de l'expédition.
- [ ] Remboursement et annulation.
- [ ] Réinitialisation de mot de passe du compte client.
- [ ] Notification d'un avis en attente de modération.
- [ ] Expéditeur lisible : `T-Pulse Archery <contact@t-pulse-archery.com>`.
- [ ] E-mails sans pixel de suivi marketing non déclaré.

## Contrôle final avant bascule du domaine

- [ ] Sauvegarder le site public GitHub Pages et la préproduction WordPress.
- [ ] Vérifier les pages FR et EN sur ordinateur et téléphone.
- [ ] Contrôler l'orthographe, les prix, les stocks, les délais et les coordonnées légales une dernière fois.
- [ ] Vérifier qu'aucune occurrence `[À COMPLÉTER` ou `[TO COMPLETE` n'est encore publiée.
- [ ] Vérifier le certificat HTTPS et les redirections du domaine.
- [ ] Retirer le mode de non-indexation uniquement lors de l'ouverture réelle.
- [ ] Effectuer une commande réelle de faible montant après la bascule, puis la rembourser si nécessaire.
- [ ] Conserver GitHub Pages accessible par son URL technique pendant la phase de retour arrière, sans modifier son contenu avant validation.

## Sources officielles utilisées pour le brouillon juridique

- Guide DGCCRF du vendeur e-commerce : https://www.economie.gouv.fr/files/files/directions_services/dgccrf/media-document/guide-dgccrf-vendeur-e-commerce.pdf
- Conditions générales de vente : https://entreprendre.service-public.fr/vosdroits/F33527
- Obligations RGPD : https://entreprendre.service-public.fr/vosdroits/F24270
- Règles relatives aux cookies : https://www.cnil.fr/fr/cookies-et-autres-traceurs/regles/cookies
- Mentions légales OVH : https://www.ovhcloud.com/fr/terms-and-conditions/

Les pages juridiques du site sont un brouillon opérationnel fondé sur ces sources. Elles ne remplacent pas un avis juridique adapté à la situation réelle de l'entreprise.
