# Conditions d'Arrosage

Ce document explique comment le système `jardin-connecte` décide de déclencher ou non l'arrosage. La décision repose sur une **hiérarchie de conditions** stricte.

## ⚡ Résumé Rapide
Pour que l'arrosage se lance, il faut impérativement :
1.  Être dans la **Plage Horaire** autorisée.
2.  AVEC la bonne **Luminosité**.
3.  ET qu'au moins **un** seuil d'alerte soit atteint (Température, Humidité Air ou Humidité Sol).

---

## 🔍 Ordre de Priorité
Le script vérifie les conditions dans cet ordre précis. Si une condition bloquante n'est pas remplie, l'arrosage est annulé, peu importe l'état de sécheresse du sol.

### 1. 🕒 Plage Horaire (BLOQUANT)
**C'est la priorité absolue.**
Le système autorise l'arrosage uniquement si l'heure actuelle se trouve dans la zone "Nuit" définie (Extérieur de la plage).
*   L'heure doit être **inférieure à l'heure de fin** (ex: avant 8h00).
*   **OU** l'heure doit être **supérieure à l'heure de début** (ex: après 20h00).
*   *En résumé :* Si vous réglez 8h et 20h, l'arrosage est **autorisé de 20h à 8h** (la nuit) et **interdit de 8h à 20h** (le jour).

#### Cas Spéciaux (ON / OFF)
*   **Arrosage H24 (Toujours Actif) ✅** : Placez les deux curseurs sur **24h** (ou les deux sur 0h).
*   **Arrosage STOP (Toujours Inactif) ❌** : Ecartez les curseurs au maximum : Min à **0h** et Max à **24h**. (L'extérieur de la plage 0-24h n'existe pas).

### 2. ☀️ Luminosité (BLOQUANT)
C'est la seconde condition obligatoire ("Sine Qua Non").
Même si vous êtes dans la bonne plage horaire, la luminosité **doit** correspondre à votre réglage.
*   *Exemple :* Si vous réglez "Luminosité < Niveau 2" (pour arroser le soir/nuit), l'arrosage ne se déclenchera jamais en plein jour, même si le sol est sec.

### 3. 🌡️💧 Facteurs Déclencheurs (AU MOINS UN REQUIS)
Une fois les barrières "Horaire" et "Luminosité" levées, le système cherche une **raison** d'arroser. Il suffit d'**une seule** condition validée parmi les suivantes :
*   **Température** : La température de l'air est comprise dans votre fourchette (ex: entre 20°C et 30°C).
*   **Humidité Air** : L'humidité de l'air dépasse (ou descend sous) votre seuil.
*   **Humidité Sol** : L'humidité du sol dépasse (ou descend sous) votre seuil (ex: Sol trop sec < 30%).

---

## 🛡️ La Tolérance
Pour éviter que l'arrosage ne se déclenche (ou ne s'arrête) à cause d'une seule mesure erronée ou d'un nuage passager, le système utilise une **Tolérance**.

*   Le réglage "Tolérance" définit le nombre de mesures passées à examiner (ex: les 3 dernières mesures).
*   Si une condition (comme "Sol trop sec") est détectée sur **n'importe laquelle** de ces 3 dernières mesures, le système considère la condition comme valide.
*   Cela permet de "lisser" le comportement et d'être plus réactif si une mesure a été captée il y a quelques instants.

---

## 💡 Exemples Concrets

### Scénario A : Arrosage Validé ✅
*   **Réglages** : Interdit 08h-20h (donc Auto la nuit), Luminosité < 5.
*   **État** : Il est **22h00**. Il fait sombre (Lum 2).
*   **Diagnostic** :
    1.  Horaire OK (22h est > 20h, c'est la nuit).
    2.  Luminosité OK (2 < 5).
    3.  Déclencheur OK (Sol sec).
*   **Résultat** : **ARROSAGE ENCLENCHÉ**.

### Scénario B : Bloqué par l'Heure (Journée) ❌
*   **Réglages** : Interdit 08h-20h.
*   **État** : Il est **14h00**. Le sol est très sec.
*   **Diagnostic** :
    1.  Horaire KO (14h est dans la zone interdite de journée).
    2.  Le reste n'est même pas évalué.
*   **Résultat** : **PAS D'ARROSAGE**.

### Scénario C : Bloqué par la Luminosité ❌
*   **Réglages** : Interdit 08h-20h, Luminosité < 3 (Sombre/Soir), Sol < 30%.
*   **État** : Il est **21h00**. Mais il fait encore clair (Lum 4).
*   **Diagnostic** :
    1.  Horaire OK (21h > 20h).
    2.  Luminosité KO (4 n'est pas < 3).
*   **Résultat** : **PAS D'ARROSAGE** (Le système attend que la luminosité baisse).

### Scénario D : Tolérance en action ✅
*   **Réglages** : Sol < 30%. Tolérance sur 3 mesures.
*   **Historique Mesures** :
    *   Mesure T (actuelle) : Sol 32% (Humide)
    *   Mesure T-1 : Sol 29% (Sec)
    *   Mesure T-2 : Sol 31% (Humide)
*   **Diagnostic** : La mesure actuelle (32%) ne déclencherait pas l'arrosage, MAIS la mesure T-1 (29%) était valide. Grâce à la tolérance, le système retient que le seuil a été atteint récemment.
*   **Résultat** : **ARROSAGE ENCLENCHÉ** (si Horaire et Lumière OK).
