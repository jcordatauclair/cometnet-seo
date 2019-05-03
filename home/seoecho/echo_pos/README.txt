echo (classification automatique binaire)


Entrée 
Le serveur echo prend en entrée une liste d'exemples étiquetés 0, 1 ou 2. 
•	0 signifie non jugé
•	1 signifie jugé pertinent
•	2 signifie jugé non pertinent
par exemple : 001 1 carre grand haut gauche ;002 1 carre petit noir bas droite ;003 2 cercle blanc droite grand ;004 0 cercle grand bas droite ;005 2 cercle petit noir bas gauche ;006 1 carre grand bas gauche ;007 1 carre grand noir droite ;008 0 cercle grand blanc droite ;009 1 carre grand haut droite ;010 0 carre grand noir droite ;mfin 
L'ordre de présentation des exemples n'a pas d'importance, de même que l'ordre des descripteurs dans les exemples. 

Sortie 
Le serveur retourne pour chacun des exemples non jugés un score et une probabilité de pertinence. 
pour l'exemple donné plus haut on obtient : 004:0,000000:0,050000;008:0,000000:0,050000;010:0,000046:1,000000;
Les exemples 004 et 008 ont une probabilité de pertinence minimale (le minimum est 0,05). L'exemple 010 a un score de 0,000046 et une probabilité de pertinence de 1,00. 

Principes de fonctionnement 
Dans un premier temps, un réseau associatif liant les différents descripteurs à la propriété d'être pertinent est construit sur la base des exemples pertinents et non pertinents.
Puis, en utilisant ce réseau, un echo est calculé sur la base de ses descripteurs pour chacun des exemples non jugés. La position de ce score sur la distribution des scores des exemples pertinents et non pertinents permet d'estimer une probabilité de pertinence pour chacun des exemples non étiquetés. Le résultat est retourné et le réseau est détruit.

Commentaires 
Il s'agit de la version la plus simple du serveur. D'abord le calcul de l'écho donne une importance identique aux propagations bottom-up et top-down. Ensuite un descripteur ne peut pas avoir un impact négatif sur le score (au pire il est nul). Les variantes echon, echop, echonp divergent sur ces points.
Enfin, un réseau associatif est reconstruit à chaque requête. Rien n'est gardé en mémoire. Les requêtes sont donc totalement indépendantes (pas de partage de données en mémoire) et peuvent donc être traitées en parallèle (d'où l'intérêt de multiplier le nombre de threads). 


Variantes 
echon :
Mêmes entrées, mêmes sorties.
La seule chose qui change concerne le fait qu'un descripteur peut avoir un impact négatif sur le score (pas ça !)

echop :
Mêmes entrées, mêmes sorties. 
La seule chose qui change concerne le fait que les propagations bottom-up et top-down peuvent avoir des importances différentes. Le paramètre p qui régle cette importance relative est optimisé (les valeurs 0, 0.5, 1.0, 1.5 et 2.0 sont essayées sur les données d'apprentissage). On peut considérer qu'un p faible s'applique au cas où les exemples pertinents ne se ressemblent pas forcément. Il pourrait être intéressant de retourner aussi le p optimal (cela informe de la nature du problème).

echonp :
mélange de echop et echon
mêmes entrées, mêmes sorties.
Deux choses changent par rapport à echo : 
•	un descripteur peut avoir un impact négatif sur le score (pas ça)
•	les propagations bottom-up et top-down peuvent avoir des poids différents, le paramètre p qui contrôle ce poids est optimisé
