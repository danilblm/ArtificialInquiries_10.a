# Modèles de ressources (resource templates) – vocabulaire commun `artinq`

Ces 4 modèles sont **identiques** aux modèles communs du cours
(`samszo/ArtificialInquiries/comparaison-templates/templates-communs/`) :

| Fichier            | Modèle commun (`o:label`)                        | Classe              |
|--------------------|--------------------------------------------------|---------------------|
| `Tache.json`       | Artificial Inquiries – Tâche                     | `artinq:Tache`      |
| `LLM.json`         | Artificial Inquiries – Modèle de langage (LLM)   | `artinq:LLM`        |
| `Prompt.json`      | Artificial Inquiries – Prompt                    | `artinq:Prompt`     |
| `ReponseLLM.json`  | Artificial Inquiries – Réponse d'un LLM          | `artinq:ReponseLLM` |

Les anciens modèles `Task.json` et `Result.json` (vocabulaire de départ, termes absents
du vocabulaire commun : `taskId`, `usesLLM`, `hasPrompt`, `producesResult`, `content`,
`problem`, `improvement`) ont été retirés.

## Import dans la base commune

Admin Omeka S (https://humanum-p8.fr/omk_artificialinquiries/admin) →
**Modèles de ressources** → **Importer** → choisir le fichier `.json` → associer les
propriétés proposées (vocabulaire *Artificial Inquiries* / *Dublin Core*) → **Importer**.

Si un modèle du même nom existe déjà dans la base, ne pas le réimporter : `response.html`
retrouve les modèles par leur nom exact (`o:label`).
