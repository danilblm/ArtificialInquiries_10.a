# Artificial Inquiries - Exercice 10.a

## Room for Improvement

### Task

- Task ID : Z7
- Type : Email
- Modèle utilisé : ChatGPT

### Problèmes rencontrés

La réponse était compréhensible, mais elle était assez générale et ne
prenait pas suffisamment en compte certains détails de la situation.
J'ai dû modifier certaines parties avant de pouvoir utiliser le résultat.

### Pourquoi ?

Le prompt ne donnait probablement pas assez de contexte sur la situation,
le destinataire et le ton attendu. Le modèle a donc produit une réponse
assez générale.

### Ce qui pourrait être amélioré

Il faudrait donner plus de contexte dans le prompt et préciser le
destinataire, la situation et le ton souhaité.

### Diagramme

Le diagramme de classes de l'exercice est disponible dans le fichier
`diagram_class.md`.

---

## L'application

**Lien :** https://danilblm.github.io/ArtificialInquiries_10.a/response.html

L'exercice papier est transformé en application web : les réponses sont
enregistrées dans la base Omeka S commune du cours
(https://humanum-p8.fr/omk_artificialinquiries), avec le vocabulaire commun `artinq`.
L'interface est disponible en **français** et en **anglais** (bouton FR / EN en haut).

### Données manipulées

| Classe (vocabulaire commun) | Anglais | Modèle de ressource |
|---|---|---|
| `artinq:Tache` | Task | Artificial Inquiries – Tâche |
| `artinq:LLM` | Language model | Artificial Inquiries – Modèle de langage (LLM) |
| `artinq:Prompt` | Prompt | Artificial Inquiries – Prompt |
| `artinq:ReponseLLM` | LLM output | Artificial Inquiries – Réponse d'un LLM |

Chaque item créé par l'application est relié à l'item « Exercice 10.a »
(`artinq:porteSur`) : seuls ces items sont affichés. Les modèles sont dans
le dossier `resource-template/`.

### Se connecter

| Méthode | Droits |
|---|---|
| **Local** : email du compte Omeka + clé API (`key_identity`, `key_credential`) | Lecture et écriture |
| **GitHub**, **Google**, **CAS Paris 8** | Écriture si le compte est autorisé sur la passerelle, sinon lecture seule |
| **Continuer en lecture seule** | Lecture seule |

Les clés API se créent dans Omeka : *Utilisateurs → ton compte → onglet « Clés API »*.
L'email sert à reconnaître tes items : chacun ne peut **modifier ou supprimer
que ses propres items**.

### Tester le CRUD

1. Se connecter en **Local** avec son email Omeka et sa clé API.
2. **Create** : choisir un onglet (Tâche, LLM, Prompt, Réponse LLM) → « + Nouveau » → remplir → « Créer ».
3. **Read** : l'item apparaît dans la liste.
4. **Update** : « Modifier » → changer un champ → « Enregistrer ».
5. **Delete** : « Supprimer » → confirmer.

### L'enquête

À la connexion, l'application :
- retrouve ou crée le **participant** (`artinq:Participant`) à partir du compte
  de connexion (`foaf:account` et `foaf:accountServiceHomepage`, par exemple « danilblm - GitHub ») ;
- permet de **choisir ou créer une enquête** (`artinq:Enquete`, reliée au participant par `artinq:hasParticipant`) ;
- affiche **tous les exercices**, faits ou à faire pour l'enquête en cours, avec un lien vers chacun
  (le lien transmet `?enquete=` et `?participant=`).

Quand un item est créé, l'exercice 10.a est marqué comme fait : une **passation**
(`artinq:Passation`) est créée, qui porte sur l'exercice (`artinq:porteSur`), est réalisée
par le participant (`artinq:realiseePar`) et rattachée à l'enquête (`dcterms:isPartOf`).

**Exercices liés** (`dcterms:relation` sur l'item « Exercice 10.a ») :
2.a (tâches), 7.b (réponses) et 8 (réponses des LLM).

### Architecture

```
response.html (GitHub Pages) → omk.php (alwaysdata) → API Omeka S commune
                             → auth.php (alwaysdata) → GitHub / Google / CAS Paris 8
```

- **`omk.php`** (relais) : la base commune n'envoie pas d'en-tête CORS, la page
  ne peut donc pas l'appeler directement. Le relais, hébergé sur alwaysdata,
  transmet les requêtes à l'API Omeka et autorise uniquement la page de l'exercice.
- **`auth.php`** (passerelle d'authentification, dossier `auth/`) : vérifie l'identité
  auprès de GitHub, Google ou du CAS, puis renvoie la clé Omeka si le compte est
  autorisé. Les secrets sont dans `config.php`, sur le serveur uniquement
  (modèle : `auth/config.example.php`).
