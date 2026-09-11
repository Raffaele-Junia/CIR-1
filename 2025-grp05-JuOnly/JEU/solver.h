#pragma once
#define TAILLE 15
#include "objets.h" 
#include <time.h>
#include <limits.h>
#include "concepteur.h"
#include "file.h"
// inverse les colonnes de tab
void inverser_colonnes(Obstacle* tab);
// fusionne les deux tableaux passés en paramètre
Obstacle* fusionner_grilles(Obstacle* gauche, Obstacle* droite);
// trouve le chemin le plus court dans tab entre départ et arrivee s'il existe
CHEMIN est_accessible(Obstacle* tab, COORDONNEE depart, COORDONNEE arrivee, int taille);
// supprime le point d'indice idx du tableau points
void supprimer_point(COORDONNEE* points, int* nb_points, int idx);
// trouve le point le plus proche de point dans tab
COORDONNEE plus_proche(COORDONNEE* tab, int nb_coos, COORDONNEE point);
// trouve les coordonnées des points intermédiaires (clé et pièce) dans tab
CHEMIN points_intermediaires(Obstacle* tab, int taille);
// trouve le chemin entre depart et arrivee en passant par les points intermédiaires
CHEMIN chemin_avec_points_intermediaire(Obstacle* tab, COORDONNEE depart, COORDONNEE arrivee, CHEMIN points_intermediaires, int taille);
// utilisé pour le chemin le plus long
void dfs_plus_long(Obstacle* tab, int taille, int x, int y, int x_arrivee, int y_arrivee, int* visite, COORDONNEE* chemin_courant, int longueur_courant, CHEMIN* meilleur_chemin);
// trouve (s'il existe) le chemin le plus long entre depart et arrivee
CHEMIN plus_long_chemin(Obstacle* tab, COORDONNEE depart, COORDONNEE arrivee, int taille);
// retourne le chemin de coordonnée sur le tableau de droite correspondant au chemin passé en paramètre
CHEMIN chemin_symetrique(const CHEMIN* chemin_fusion);
// dessine le chemin en paramètre
void dessiner_chemin(Obstacle* tab, CHEMIN chemin, int taille);
