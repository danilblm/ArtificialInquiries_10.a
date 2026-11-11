# Tests des IHM des exercices – réponses pour le formulaire

Tests du 2 novembre 2026, faits dans Chromium (navigateur sans interface) sur les 16 exercices de la
classe `artinq:Exercice` (id 134) de la base commune
<https://humanum-p8.fr/omk_artificialinquiries>, en lecture seule (sans clé API).

**Constat général** : l'API de la base commune **n'envoie aucun en-tête CORS**
(`Access-Control-Allow-Origin` absent, `OPTIONS` → 405). Une page GitHub Pages qui appelle
directement `https://humanum-p8.fr/omk_artificialinquiries/api` est donc bloquée par le navigateur,
même pour la lecture. Seules les IHM qui passent par un **relais serveur** (PHP sur alwaysdata)
arrivent à se connecter : 2.a, 8 et 10.a.

**Create / Update / Delete** : il n'y avait aucune clé API Omeka dans l'environnement de test. Pour
chaque IHM, j'ai vérifié que l'écriture **sans clé est refusée** (sécurité OK). Une vraie création
demande ta clé (voir « À faire avec ta clé » en bas). Légende : **OK**, **KO**, **NT** (non testable
sans clé ou sans compte).

## Tableau récapitulatif

| Ex.  | Auteur·e                 | Connexion base omk | Create | Read | Update | Delete |
|------|--------------------------|--------------------|--------|------|--------|--------|
| 1    | Antoine Jarnoux          | KO (CORS)          | KO     | KO   | KO     | KO     |
| 2.a  | Imene Lalaoui            | OK (relais)        | NT     | OK   | NT     | NT     |
| 2.b  | Faris Guennoun           | KO (CORS)          | KO     | KO   | KO     | KO     |
| 3.a  | Leiticia Atmane          | KO (CORS)          | KO     | KO   | KO     | KO     |
| 3.b  | Lamiaa Laassel           | KO (404)           | KO     | KO   | KO     | KO     |
| 4    | Lena Harchaoui           | KO (CORS)          | KO     | KO   | KO     | KO     |
| 5    | Hakima Lakab             | KO (CORS)          | KO     | KO   | KO     | KO     |
| 6    | Zakaria Radi             | KO (404 PHP)       | KO     | KO   | KO     | KO     |
| 6.c  | Hatim Nachit             | KO (tunnel mort)   | KO     | KO   | KO     | KO     |
| 7.b  | Mohcine Boutahiri        | KO (pas de backend)| KO     | KO   | KO     | KO     |
| 8    | Maroua Benjelloun Touimi | OK (relais)        | NT     | OK   | NT     | NT     |
| 9    | Adrien Limache           | KO (page 404)      | KO     | KO   | KO     | KO     |
| 10.a | Danil Bellahmer (moi)    | OK (relais)        | NT     | OK   | NT     | NT     |
| 11   | Tesline Hassan Okieh     | NT (Firebase)      | NT     | NT   | NT     | NT     |
| 12   | Taise de Thèse Yabie     | KO (page 404)      | KO     | KO   | KO     | KO     |
| 13   | Ines Kasdi               | KO (CORS)          | –      | KO   | –      | –      |

## Réponses détaillées (à copier dans le formulaire)

### Exercice 1 – Antoine Jarnoux
- Connexion base omk : **KO**. Par défaut l'API est `http://localhost/omeka-s/api/` (XAMPP). Avec l'adresse de la base commune dans « Paramètres », les 5 requêtes de démarrage sont bloquées par CORS.
- Create / Read / Update / Delete : **KO**, aucune donnée chargée.
- Commentaire : l'interface est complète (connexion Omeka/GitHub/Google/CAS, passations, outil de dessin) et le message d'erreur est clair. Il manque un relais serveur pour joindre la base commune depuis GitHub Pages.

### Exercice 2.a – Imene Lalaoui
- Connexion base omk : **OK**. La page GitHub Pages redirige vers `imene-artinq.alwaysdata.net`, qui passe par un relais `omeka/proxy.php`.
- Read : **OK**. Les templates, les propriétés et les items sont chargés. On voit l'utilisateur « imene » et sa tâche « rediger des emails » (Quotidienne, J'aime, 30 min, Débutant).
- Create / Update / Delete : **NT**. Sans session, le relais refuse l'écriture (`Connexion Omeka S requise`), ce qui est correct. Il faut se connecter avec une clé Omeka.
- Commentaire : fonctionne sur la base commune et respecte la sécurité.

### Exercice 2.b – Faris Guennoun
- Connexion base omk : **KO**. « Test connection » vers la base commune est bloqué par CORS (`items`, `properties`, `resource_classes`).
- C / R / U / D : **KO**.
- Commentaire : l'interface est soignée (formulaire de tâche détaillé). La page avertit elle‑même du problème CORS. Elle garde aussi les clés API dans le localStorage.

### Exercice 3.a – Leiticia Atmane
- Connexion base omk : **KO**. Par défaut l'API est `http://localhost/omekas/api`. Avec la base commune, la page affiche « Omeka répond, mais il n'autorise pas cette page (CORS) ».
- C / R / U / D : **KO**.
- Commentaire : le diagnostic CORS est très clair et le CRUD des sessions « Impressions à chaud » est complet côté interface. Il faudrait un relais pour la base commune.

### Exercice 3.b – Lamiaa Laassel
- Connexion base omk : **KO**. La page appelle `/auth/check.php` sur github.io, qui renvoie 404 (GitHub Pages n'exécute pas le PHP). La connexion est impossible.
- C / R / U / D : **KO** (« Connectez-vous pour afficher les ressources »).
- Commentaire : le backend PHP n'est pas hébergé. La liste des classes est écrite en dur.

### Exercice 4 – Lena Harchaoui
- Connexion base omk : **KO**. L'API par défaut est localhost (XAMPP). La base commune est bloquée par CORS, y compris avec « Continuer sans compte ».
- C / R / U / D : **KO**.
- Commentaire : l'interface CRUD est complète (Omeka, GitHub avec jeton, Google et CAS non configurés).

### Exercice 5 – Hakima Lakab
- Connexion base omk : **KO**. La page propose la base commune, mais l'appel est bloqué par CORS (« Impossible de joindre l'API Omeka S »).
- C / R / U / D : **KO**.
- Commentaire : l'interface est complète, avec la détection automatique du vocabulaire et le bouton Google qui s'affiche. Ses templates « Ex5 » (ids 29 à 36) sont bien dans la base commune.

### Exercice 6 – Zakaria Radi
- Connexion base omk : **KO**. `server/api.php?action=status` renvoie 404 sur GitHub Pages. La page affiche l'erreur `Unexpected token '<' … is not valid JSON`.
- C / R / U / D : **KO**.
- Commentaire : la page dépend d'un backend PHP qui n'est pas hébergé.

### Exercice 6.c – Hatim Nachit
- Connexion base omk : **KO**. « Charger depuis Omeka S » appelle un tunnel temporaire `*.trycloudflare.com` qui n'existe plus (« Failed to fetch »).
- C / R / U / D : **KO** sur Omeka. Les boutons Enregistrer / Modifier / Supprimer ne fonctionnent qu'en localStorage, avec un export JSON.
- Commentaire : le formulaire « What's on the Line? » est complet, mais il n'y a pas de connexion durable à la base commune.

### Exercice 7.b – Mohcine Boutahiri
- Connexion base omk : **KO**. `API_BASE_URL` est vide, la page indique « API non connectée — backend distant non configuré ».
- C / R / U / D : **KO**.
- Commentaire : l'interface est prête (tableau des réponses, ajout, suppression), mais le backend n'est pas déployé.

### Exercice 8 – Maroua Benjelloun Touimi
- Connexion base omk : **OK**. Le relais `maroua-artinq8.alwaysdata.net/api/responses.php` affiche « Connexion réussie à la base Omeka S commune ».
- Read : **OK**, mais avec 0 réponse affichée. C'est cohérent : la base contient 0 item `artinq:ReponseLLM`.
- Create / Update / Delete : **NT**. Ces actions passent par des pages alwaysdata protégées par mot de passe (`new-response.php`, `manage-responses.php`). La protection est correcte, mais je n'ai pas le mot de passe.
- Commentaire : utilise bien le template commun « Réponse d'un LLM » (18). GitHub, Google et CAS ne sont pas configurés.

### Exercice 9 – Adrien Limache
- Connexion base omk : **KO**. L'URL `AdrienLIMACHE.github.io/ArtificialInquiries_9/response.html` renvoie 404 (GitHub Pages n'est pas activé).
- C / R / U / D : **KO**.

### Exercice 10.a – Danil Bellahmer (mon exercice)
- Connexion base omk : **OK** par le relais `bellahmer.alwaysdata.net/auth/omk.php` (CORS limité à `https://danilblm.github.io`, méthodes GET/POST/PUT/PATCH/DELETE).
- Read : **OK**. Les 4 classes communes sont chargées (Tâche 1, LLM 1, Prompt 0, Réponse LLM 0).
- Create / Update / Delete : **NT** sans clé. En lecture seule, les boutons Modifier et Supprimer sont désactivés. Un `POST` anonyme via le relais est refusé par Omeka (`Permission denied … create`), ce qui est correct.
- Bug trouvé et corrigé : les listes de liens proposaient tous les items de toutes les classes. La Tâche 34 « ,, » a ainsi été enregistrée avec elle‑même comme Prompt et comme Réponse. Maintenant « Utilise le LLM » et « Produite par » ne proposent que des LLM, « A pour prompt » que des Prompts et « Produit la réponse » que des Réponses LLM.
- Données de test à nettoyer dans la base : item 34 (Tâche « ,, ») et item 35 (LLM « nn »), créés le 27/10.

### Exercice 11 – Tesline Hassan Okieh
- Connexion base omk : **NT**. La page exige d'abord une connexion Google ou GitHub (Firebase). L'API par défaut est `localhost/omeka-s-4.2.1`, et elle appelle l'API en direct, donc la base commune serait bloquée par CORS.
- C / R / U / D : **NT** (pas de compte de test).

### Exercice 12 – Taise de Thèse Yabie
- Connexion base omk : **KO**. L'URL `gihamos.github.io/ArtificialInquiries_12/response.html` renvoie 404.
- C / R / U / D : **KO**.

### Exercice 13 – Ines Kasdi
- Connexion base omk : **KO**. L'appel à `…/api/items?per_page=100` est bloqué par CORS (« Impossible de charger les exercices »).
- Read : **KO**. Create / Update / Delete : pas d'interface d'écriture (liste des exercices en lecture seule).

## Resource templates dans la base commune : OK

Les 4 modèles sont présents dans la base et **identiques** aux fichiers de `resource-template/`
(mêmes propriétés, dans le même ordre) :

| Fichier           | Id base | Modèle                                           | Classe              | Propriétés |
|-------------------|---------|--------------------------------------------------|---------------------|------------|
| `LLM.json`        | 17      | Artificial Inquiries – Modèle de langage (LLM)   | `artinq:LLM`        | 8          |
| `ReponseLLM.json` | 18      | Artificial Inquiries – Réponse d'un LLM          | `artinq:ReponseLLM` | 11         |
| `Tache.json`      | 19      | Artificial Inquiries – Tâche                     | `artinq:Tache`      | 31         |
| `Prompt.json`     | 20      | Artificial Inquiries – Prompt                    | `artinq:Prompt`     | 3          |

Le propriétaire de ces modèles est l'utilisateur Omeka n°5, le même que celui des items 34 et 35.

## À faire avec ta clé (pour passer 10.a en OK sur C/U/D)

1. Ouvrir https://danilblm.github.io/ArtificialInquiries_10.a/response.html et se connecter en « Local » avec `key_identity` et `key_credential`.
2. **Create** : onglet Prompt → « + Nouveau » → titre `TEST 10.a`, texte `essai` → Créer. Le prompt doit apparaître dans la liste.
3. **Read** : il apparaît avec son texte. Il apparaît aussi dans `…/admin/item?resource_class_id=145`.
4. **Update** : Modifier → texte `essai modifié` → Enregistrer. La liste est mise à jour.
5. **Delete** : Supprimer → confirmer. Le prompt disparaît.
6. Supprimer aussi les items 34 et 35 (données de test).
