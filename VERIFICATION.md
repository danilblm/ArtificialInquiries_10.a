# Vérification – exercice 10.a

## 1. Resource templates ↔ vocabulaire commun : OK

Les 4 modèles de `resource-template/` sont identiques (même JSON) aux modèles communs
`artinq_Tache`, `artinq_LLM`, `artinq_Prompt`, `artinq_ReponseLLM`. Toutes les classes et
propriétés existent dans `artinq-commun.ttl`. Les anciens `Task.json` / `Result.json` ont été retirés.

Correspondance ancien vocabulaire (`artificial_inquiries.ttl`, `diagram_class.md`) → commun :

| Ancien                 | Commun                          |
|------------------------|---------------------------------|
| `artinq:Task`          | `artinq:Tache`                  |
| `artinq:Result`        | `artinq:ReponseLLM`             |
| `artinq:taskId`        | `dcterms:identifier`            |
| `artinq:type`          | `dcterms:type`                  |
| `artinq:name`          | `dcterms:title`                 |
| `artinq:content`       | `artinq:texte`                  |
| `artinq:problem`       | `artinq:probleme`               |
| `artinq:cause`         | `artinq:cause`                  |
| `artinq:improvement`   | `artinq:amelioration`           |
| `artinq:usesLLM`       | `artinq:utiliseLLM`             |
| `artinq:hasPrompt`     | `artinq:aPourPrompt`            |
| `artinq:producesResult`| `artinq:produitReponse`         |

## 2. Import dans la base commune

Voir `resource-template/README.md`. À faire dans l'admin Omeka (compte requis).

## 3. IHM (`response.html`) ↔ vocabulaire et templates communs : OK

- Classes utilisées : `artinq:Tache`, `artinq:LLM`, `artinq:Prompt`, `artinq:ReponseLLM`.
- Modèles cherchés par leur nom commun exact.
- Tous les champs affichés existent dans le template commun de leur classe :
  - Tâche : `dcterms:title`*, `dcterms:identifier`, `dcterms:type`, `artinq:utiliseLLM`, `artinq:aPourPrompt`, `artinq:produitReponse`
  - LLM : `dcterms:title`*, `artinq:version`, `artinq:fournisseur`
  - Prompt : `dcterms:title`*, `artinq:texte`
  - Réponse LLM : `dcterms:title`*, `artinq:texte`, `artinq:probleme`, `artinq:cause`, `artinq:amelioration`, `artinq:produitePar`

  (* obligatoire)
- Point d'attention : les appels à la base commune passent par le relais
  `https://bellahmer.alwaysdata.net/auth/omk.php`, dont le code **n'est pas dans le dépôt**.

## 4. Tests à faire (pour le formulaire)

| Test                          | Comment tester                                                     | Résultat |
|-------------------------------|--------------------------------------------------------------------|----------|
| Connexion à la base omk       | Ouvrir `response.html`, la liste des Tâches se charge              | OK / KO  |
| Connexion Omeka (clés API)    | Onglet clés : saisir key_identity + key_credential                 | OK / KO  |
| Connexion GitHub              | Bouton GitHub → retour sur la page connecté                        | OK / KO  |
| Connexion Google              | Bouton Google → retour connecté                                    | OK / KO  |
| Connexion CAS                 | Bouton CAS → retour connecté                                       | OK / KO  |
| Create                        | Ajouter un Prompt « test » → il apparaît dans la liste             | OK / KO  |
| Read                          | Ouvrir le Prompt « test » → champs affichés                        | OK / KO  |
| Update                        | Modifier son texte → enregistrer → la modification apparaît        | OK / KO  |
| Delete                        | Supprimer le Prompt « test » → il disparaît                        | OK / KO  |
