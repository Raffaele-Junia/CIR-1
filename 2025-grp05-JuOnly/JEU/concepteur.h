#pragma once
#define TAILLE 15
#include "objets.h" 
#include <time.h>
#define MAX_LONGUEUR_CHEMIN 1000
#include <limits.h>

typedef struct {
    COORDONNEE gauche;
    COORDONNEE droite;
} COORD_dep_sortie;


typedef struct {
    COORDONNEE points[MAX_LONGUEUR_CHEMIN];
    int longueur;
} CHEMIN;


typedef struct {
    COORDONNEE pos;
    int prev_idx;
} NoeudBFS;

// crée un tableau d'obstacles de taille taille * taille
Obstacle* creer_tab(int taille); 
// remplit le tableau d'obstacles avec des murs 
void remplir_mur(Obstacle* tab, int taille); 
// affiche la grille entière dans la console
void afficher_grille_entiere(Obstacle* gauche, Obstacle* droite, int taille); 
// génère une position de départ aléatoire pour le labyrinthe
COORD_dep_sortie depart_aleatoire(Obstacle* gauche, Obstacle* droite, int taille);
// génère une position d'arrivée aléatoire pour le labyrinthe
COORD_dep_sortie arrivee_aleatoire(Obstacle* gauche, Obstacle* droite, int taille);
// creuse un mur dans le tableau d'obstacles
void creuser_mur(Obstacle* tab, int x, int y); 
// utilisé pour savoir quelle case est visitée
int a_voisin_visite(int* visite, int taille, int x, int y, int px, int py); 
// génère un chemin aléatoire entre deux points
CHEMIN chemin_aleatoire(Obstacle* tab, COORDONNEE depart, COORDONNEE arrivee); 
// génère un chemin aléatoire entre deux points de manière récursive
int chemin_aleatoire_rec(Obstacle* tab, int taille, COORDONNEE courant, COORDONNEE arrivee, int* visite, CHEMIN* chemin, int px, int py); 
// permet d'élargir les passages du labyrinthe
void elargir_passages(Obstacle* tab, int taille, int probabilite, int colonne_sortie);
// vérifie si la sortie est accessible
void verifier_sortie(Obstacle* gauche, Obstacle* droite, int taille, COORDONNEE sortie_gauche, COORDONNEE sortie_droite);
// génère un labyrinthe
void generer_labyrinthe(Obstacle* tab, int taille, int x, int y, int x_arrivee, int y_arrivee, int colonne_sortie, int* visite);
// fusionne les deux tableaux d'obstacles en un seul tableau de symboles
Obstacle* fusion_cotes(Obstacle* gauche, Obstacle* droite, int taille); 
// génère un tableau de symboles à partir du tableau fusionné
char* tableau_symboles(Obstacle* fusion, int taille);
// génère des portes devant la sortie
void generer_porte_sortie(Obstacle* tab, int taille, COORDONNEE sortie); 
// génère une clé ou une pièce
void generer_cle_piece(Obstacle* gauche, Obstacle* droite, int taille, int lequel, CHEMIN chemin);
// génère des vides dans le labyrinthe
void generer_vides(Obstacle* tab, int taille, int probabilite, CHEMIN chemin);
// niveau facile
void aleatoire_difficulte_facile();
// niveau moyen
void aleatoire_difficulte_moyenne();
// niveau difficile
void aleatoire_difficulte_max(); 
