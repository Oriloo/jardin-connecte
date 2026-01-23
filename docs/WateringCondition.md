# Conditions d'Arrosage

> Ce document explique comment le système `jardin-connecte` décide de déclencher ou non l'arrosage. La décision repose sur une **hiérarchie de conditions** stricte.

## ⚡ Résumé Rapide
Pour que l'arrosage se lance, il faut impérativement :
1.  Être dans la **Plage Horaire** autorisée.
2.  AVEC la bonne **Luminosité**.
3.  Dans la bonne plage de **Température**.
4.  ET qu'au moins **un** seuil d'arrosage soit atteint (Humidité Air ou Humidité Sol).

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
La **Luminosité Ambiante** est une condition obligatoire.
*   *Exemple :* Si vous réglez "Luminosité < Niveau 2" (pour arroser le soir/nuit), l'arrosage ne se déclenchera jamais en plein jour, même si le sol est sec.

### 3. 🌡️ Température (BLOQUANT)
La **Température de l'air** est une condition obligatoire.
*   Si la température de l'air n'est pas comprise dans la plage définie (ex: entre 10°C et 30°C), l'arrosage ne se lancera pas.
*   Cela permet d'éviter d'arroser s'il fait trop froid (gel) ou trop chaud (évaporation immédiate).

### 4. 💧 Facteurs Déclencheurs (AU MOINS UN REQUIS)
Une fois les conditions bloquantes (Horaire, Luminosité, Température) validées, le système vérifie si l'arrosage est nécessaire. Il suffit d'**une seule** condition validée :
*   **Humidité Air** : L'humidité de l'air dépasse (ou descend sous) votre seuil.
*   **Humidité Sol** : L'humidité du sol dépasse (ou descend sous) votre seuil (ex: Sol trop sec < 30%).

---

## 🛡️ La Tolérance
Pour éviter que l'arrosage ne se déclenche (ou ne s'arrête) à cause d'une seule mesure erronée ou d'un nuage passager, le système utilise une **Tolérance**.

*   Le réglage "Tolérance" définit le nombre de mesures passées à examiner (ex: les 3 dernières mesures).
*   Si une condition (comme "Sol trop sec") est détectée sur **n'importe laquelle** de ces 3 dernières mesures, le système considère la condition comme valide.
*   Cela permet de "lisser" le comportement et d'être plus réactif si une mesure a été captée il y a quelques instants.
