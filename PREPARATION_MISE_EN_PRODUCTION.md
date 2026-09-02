# T-Pulse Archery - checklist avant mise en production

Dernière mise à jour : 2 septembre 2026.

Cette liste ne contient plus que les contrôles ou actions qui nécessitent l'intervention de Tony. Les corrections de contenu, de traduction, de produit, d'avis, de SEO et d'interface sont gérées dans le dépôt et déployées en préproduction.

## À faire avant d'accepter une commande réelle

- [ ] **Adresse professionnelle** : la ville seule est conservée à la demande de Tony. Une adresse géographique complète reste normalement requise pour une boutique B2C. Prévoir une domiciliation professionnelle si l'adresse personnelle ne doit pas être publiée.
- [ ] **Médiateur de la consommation** : aucune coordonnée de médiateur n'est publiée pour le moment. L'adhésion et l'affichage du médiateur restent légalement requis avant une ouverture commerciale durable.
- [ ] **Téléphone professionnel** : aucun numéro n'est publié. Créer un numéro professionnel distinct si l'on ne souhaite pas communiquer le numéro personnel.
- [ ] **Prix du livre** : modifier le prix éditeur dans KDP pour qu'il corresponde aux 15,00 € affichés sur la boutique, puis contrôler la fiche Amazon.
- [ ] **Stocks** : vérifier les quantités réelles des cinq variations HeliTwist et du livre dans WooCommerce.
- [x] **Stripe** : mode production activé et paiement réel validé.
- [x] **Commande réelle finale** : commande à faible montant effectuée, Stripe, WooCommerce, e-mails, Sendcloud/Mondial Relay et stock vérifiés.
- [ ] **Abby** : créer la facture à partir du bloc `Préparation facture Abby` présent dans l'administration de chaque commande, puis l'envoyer au client depuis Abby.
- [ ] **Sendcloud** : point Mondial Relay confirmé dans Sendcloud. Étiquette non générée pour éviter une facturation inutile ; à générer lors de la première vraie expédition.
- [ ] **Sauvegarde** : configurer UpdraftPlus vers un stockage extérieur à OVH et effectuer au moins un test de restauration.
- [ ] **E-mails** : vérifier une nouvelle commande, une commande terminée, un remboursement, une réinitialisation de mot de passe et une notification d'avis en attente.

## DNS et délivrabilité e-mail

- [x] SPF OVH présent.
- [ ] Vérifier DKIM dans les en-têtes d'un e-mail reçu (`dkim=pass`).
- [ ] Ajouter chez OVH un enregistrement TXT nommé `_dmarc` avec cette valeur de démarrage : `v=DMARC1; p=none; rua=mailto:contact@t-pulse-archery.com; adkim=r; aspf=r; pct=100`.
- [ ] Après plusieurs semaines sans erreur SPF/DKIM, renforcer progressivement DMARC vers `p=quarantine`, puis éventuellement `p=reject`.

## Contrôles de contenu

- [x] Mention HeliTwist corrigée en `Demande de brevet déposée - FR2506128`.
- [x] Choix du filetage obligatoire avant ajout au panier.
- [x] Stocks indépendants pour `5/16`, `1/4`, `M8`, `5/16 + 1/4` et `M8 + 1/4`.
- [x] Livre à 15,00 €, ISBN-13, poids et dimensions renseignés.
- [x] Avis Amazon présentés séparément avec lien vers Amazon, sans recopier les commentaires.
- [x] Formulaire d'avis simplifié : une seule identité, e-mail privé, modèle, date, note et texte.
- [ ] Remplacer l'article d'accueil du blog par une première vraie actualité lorsque celle-ci est prête.
- [ ] Mettre à jour périodiquement la note Amazon affichée si elle évolue.

## Contrôles techniques au moment de la bascule

- [ ] Sauvegarder le site GitHub Pages actuel et la préproduction WordPress.
- [ ] Conserver l'ancienne version GitHub accessible par son URL technique pendant la période de retour arrière.
- [ ] Basculer le domaine vers OVH et remplacer les URL de préproduction par `https://t-pulse-archery.com`.
- [ ] Regénérer ou contrôler Complianz sur le domaine final.
- [ ] Retirer `noindex` uniquement après la bascule et les contrôles finaux.
- [ ] Vérifier les redirections HTTPS, les en-têtes de sécurité, les liens FR/EN, les canonicals et le sitemap.
- [ ] Réactiver Google Analytics uniquement via Complianz et après consentement, si les statistiques sont souhaitées.

## Ce qui est volontairement différé

- PayPal : non installé tant que son intérêt commercial n'est pas démontré.
- Automatisation Abby : facturation manuelle assistée par le bloc de copie WooCommerce.
- Politique CSP stricte : à mettre en place après validation définitive des domaines Stripe et Sendcloud afin de ne pas casser le paiement ou le sélecteur de point relais.

## Sources juridiques de référence

- Garanties légales et encadré obligatoire : https://www.legifrance.gouv.fr/codes/id/LEGIARTI000045981318/
- Conditions générales de vente : https://entreprendre.service-public.fr/vosdroits/F33527
- Médiation de la consommation : https://www.economie.gouv.fr/dgccrf/les-fiches-pratiques/la-mediation-de-la-consommation-ce-que-vous-devez-savoir
- Mentions obligatoires d'un site professionnel : https://www.economie.gouv.fr/entreprises/developper-son-entreprise/innover-et-numeriser-son-entreprise/mentions-sur-votre-site-internet-les-obligations-respecter
- Cookies : https://www.cnil.fr/fr/cookies-et-autres-traceurs/regles/cookies

Les textes publiés constituent une base opérationnelle. La ville seule, l'absence de téléphone et l'absence de médiateur restent des écarts connus aux obligations généralement applicables à une boutique B2C.
