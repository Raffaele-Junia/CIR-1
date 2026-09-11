#pragma once

typedef struct COORDONNEE {
    int x;
    int y;
} COORDONNEE;

typedef struct Player {
    COORDONNEE lescos;
    int cle;     // Nombre de clés possédées
    int pieces;  // Nombre de pièces collectées
} Player;

typedef struct Obstacle {
    COORDONNEE lescos;
    int lequel;  // Type d'obstacle
} Obstacle;

// Variables globales externes (définies dans un .c)
extern Obstacle obstacles[];  
extern int nb_obstacles;      
extern Player player;
extern int niveau_actuel;  

// Fonctions
Obstacle creer_obstacle(int x, int y, int lequel);
char symbole_obstacle(int lequel);
void afficher_obstacle(const Obstacle *obs);

/* Types d'obstacles (valeurs possibles de "lequel") */
/* 1  - Murs            */
/* 2  - Vide            */
/* 3  - Tonneaux à pousser */
/* 4  - Clés            */
/* 5  - Portes          */
/* 6  - Dehors          */
/* 7  - Pièces          */
/* 8  - Sortie          */
/* 9  - Sol             */
/* 10 - Point départ gauche */
/* 11 - Point départ droite */
/* 12 - Miroir          */
/* 13 - chemin */