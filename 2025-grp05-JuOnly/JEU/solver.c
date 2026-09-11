#include "objets.h"  
#include <windows.h>  
#include "solver.h"  
#include <stdlib.h>
#include <stdbool.h>
#include "file.h"


void inverser_colonnes(Obstacle* tab) {
    for (int i = 0; i < TAILLE; i++) {
        for (int j = 0; j < TAILLE / 2; j++) {
            int idx1 = i * TAILLE + j; 
            int idx2 = i * TAILLE + (TAILLE - 1 - j); 

            Obstacle temp = tab[idx1];
            tab[idx1] = tab[idx2];
            tab[idx2] = temp;
        }
    }
}

Obstacle* fusionner_grilles(Obstacle* gauche, Obstacle* droite) {
    Obstacle* resultat = (Obstacle*)malloc(TAILLE * TAILLE * sizeof(Obstacle));
    if (resultat == NULL) {
        return NULL; 
    }

    for (int i = 0; i < TAILLE; i++) {
        for (int j = 0; j < TAILLE; j++) {
            int idx = i * TAILLE + j;

            Obstacle obs_gauche = gauche[idx];
            Obstacle obs_droite = droite[idx];

            // Fusion selon les conditions
            if (obs_gauche.lequel == 9 && obs_droite.lequel == 9) { // Sol et sol
                resultat[idx].lequel = 9; // Sol
            }
            else if ((obs_gauche.lequel == 1 && obs_droite.lequel == 9) ||
                (obs_gauche.lequel == 9 && obs_droite.lequel == 1)) { // Sol et mur
                resultat[idx].lequel = 1; // Mur
            }
            else if (obs_gauche.lequel == 1 && obs_droite.lequel == 1) { // Mur et mur
                resultat[idx].lequel = 1; // Mur
            }
            else if (obs_gauche.lequel == 10 && obs_droite.lequel == 11) { //  départ
                resultat[idx].lequel = 10; // départ
            }
            else if (obs_gauche.lequel == 8 && obs_droite.lequel == 8) { // sortie 
                resultat[idx].lequel = 8; // sortie
            }
            else if (obs_gauche.lequel == 5 && obs_droite.lequel == 5) { // porte et porte
                resultat[idx].lequel = 5; // porte
            }
            else if ((obs_gauche.lequel == 4 && obs_droite.lequel == 9) ||
                (obs_gauche.lequel == 9 && obs_droite.lequel == 4)) { // clé et sol
                resultat[idx].lequel = 4; // clé
            }
            else if ((obs_gauche.lequel == 7 && obs_droite.lequel == 9) ||
                (obs_gauche.lequel == 9 && obs_droite.lequel == 7)) { // pièce et sol
                resultat[idx].lequel = 7; // pièce
            }
            else if (((obs_gauche.lequel == 4 || obs_gauche.lequel==7) && obs_droite.lequel == 1) ||
                (obs_gauche.lequel == 1 && (obs_droite.lequel == 4 || obs_droite.lequel == 7))) { // clé ou pièce et mur
                resultat[idx].lequel = 1; // Mur
            }
            else if (((obs_gauche.lequel == 4 || obs_gauche.lequel == 7) && obs_droite.lequel == 2) ||
                (obs_gauche.lequel == 2 && (obs_droite.lequel == 4 || obs_droite.lequel == 7))) { // clé ou pièce et vide
                resultat[idx].lequel = 2; // vide
            }
            else if (obs_gauche.lequel == 2 && obs_droite.lequel == 2) { // vide et vide
                resultat[idx].lequel = 2; // vide
            }
            else if ((obs_gauche.lequel == 2 && obs_droite.lequel == 9) ||
                (obs_gauche.lequel == 9 && obs_droite.lequel == 2)) { // Sol et vide
                resultat[idx].lequel = 2; // vide
            }
            else if ((obs_gauche.lequel == 1 && obs_droite.lequel == 2) ||
                (obs_gauche.lequel == 2 && obs_droite.lequel == 1)) { // vide et mur
                resultat[idx].lequel = 1; // Mur
            }

            resultat[idx].lescos = obs_gauche.lescos;
        }
    }

    return resultat;
}




CHEMIN est_accessible(Obstacle* tab, COORDONNEE depart, COORDONNEE arrivee, int taille) {
    CHEMIN chemin;
    chemin.longueur = 0;

    int* visite = (int*)calloc(taille * taille, sizeof(int));
    int* parents = (int*)malloc(taille * taille * sizeof(int));
    if (!visite || !parents) {
        if (visite) free(visite);
        if (parents) free(parents);
        return chemin;
    }

    Queue* file = NULL;
    NewQueue(&file, taille * taille);

    int idx_depart = depart.y * taille + depart.x;
    int idx_arrivee = arrivee.y * taille + arrivee.x;

    file->first = 0;
    file->last = -1;
    file->queueNbElemt = 0;

    queue(file, idx_depart);
    visite[idx_depart] = 1;
    parents[idx_depart] = -1;

    int trouve = 0;

    while (!isQueueEmpty(file)) {
        int idx_courant;
        deQueue(file, &idx_courant);

        int x = idx_courant % taille;
        int y = idx_courant / taille;

        if (x == arrivee.x && y == arrivee.y) {
            trouve = 1;
            break;
        }

        COORDONNEE dirs[4] = { {1,0},{-1,0},{0,1},{0,-1} };
        for (int d = 0; d < 4; d++) {
            int nx = x + dirs[d].x;
            int ny = y + dirs[d].y;
            if (nx >= 0 && nx < taille && ny >= 0 && ny < taille) {
                int nidx = ny * taille + nx;
                if (!visite[nidx] && (tab[nidx].lequel == 9 || tab[nidx].lequel == 8 || tab[nidx].lequel == 10 || tab[nidx].lequel == 11 || tab[nidx].lequel==4 || tab[nidx].lequel==7)) {
                    visite[nidx] = 1;
                    queue(file, nidx);
                    parents[nidx] = idx_courant;
                }
            }
        }
    }

    if (trouve) {
        int idx = idx_arrivee;
        int temp_chemin[MAX_LONGUEUR_CHEMIN];
        int len = 0;
        while (idx != -1 && len < MAX_LONGUEUR_CHEMIN) {
            temp_chemin[len++] = idx;
            idx = parents[idx];
        }
        // Inverser le chemin
        for (int i = len - 1; i >= 0; i--) {
            int idx2 = temp_chemin[i];
            chemin.points[len - 1 - i].x = idx2 % taille;
            chemin.points[len - 1 - i].y = idx2 / taille;
        }
        chemin.longueur = len;
    }
    else {
        chemin.longueur = 0;
    }

    free(visite);
    free(parents);
    freeQueue(file);
    return chemin;
}




COORDONNEE plus_proche(COORDONNEE* tab, int nb_coos, COORDONNEE point) {
    int min_dist = INT_MAX;
    int idx_min = 0;
    for (int i = 0; i < nb_coos; i++) {
        int dist = abs(tab[i].x - point.x) + abs(tab[i].y - point.y);
        if (dist < min_dist) {
            min_dist = dist;
            idx_min = i;
        }
    }
    return tab[idx_min];
}




void supprimer_point(COORDONNEE* points, int* nb_points, int idx) {
    if (idx < 0 || idx >= *nb_points) return;
    points[idx] = points[*nb_points - 1];
    (*nb_points)--;
}


CHEMIN points_intermediaires(Obstacle* tab, int taille) {
    CHEMIN chemin;
    chemin.longueur = 0;

    for (int y = 0; y < taille; y++) {
        for (int x = 0; x < taille; x++) {
            int idx = y * taille + x;
            if (tab[idx].lequel == 4 || tab[idx].lequel == 7) {
                if (chemin.longueur < MAX_LONGUEUR_CHEMIN) {
                    chemin.points[chemin.longueur].x = x;
                    chemin.points[chemin.longueur].y = y;
                    chemin.longueur++;
                }
            }
        }
    }
    return chemin;
}




void dfs_plus_long(Obstacle* tab, int taille, int x, int y, int x_arrivee, int y_arrivee,int* visite, COORDONNEE* chemin_courant, int longueur_courant,CHEMIN* meilleur_chemin) {
    if (x == x_arrivee && y == y_arrivee) {
        if (longueur_courant > meilleur_chemin->longueur) {
            meilleur_chemin->longueur = longueur_courant;
            for (int i = 0; i < longueur_courant; i++) {
                meilleur_chemin->points[i] = chemin_courant[i];
            }
        }
        return;
    }

    int idx = y * taille + x;
    visite[idx] = 1;
    chemin_courant[longueur_courant].x = x;
    chemin_courant[longueur_courant].y = y;
    longueur_courant++;

    COORDONNEE dirs[4] = { {1,0},{-1,0},{0,1},{0,-1} };
    for (int d = 0; d < 4; d++) {
        int nx = x + dirs[d].x;
        int ny = y + dirs[d].y;
        int nidx = ny * taille + nx;
        if (nx >= 0 && nx < taille && ny >= 0 && ny < taille &&
            !visite[nidx] &&
            (tab[nidx].lequel == 9 || tab[nidx].lequel == 8 || tab[nidx].lequel == 10 || tab[nidx].lequel == 11)) {
            dfs_plus_long(tab, taille, nx, ny, x_arrivee, y_arrivee, visite, chemin_courant, longueur_courant, meilleur_chemin);
        }
    }

    visite[idx] = 0; 
}

CHEMIN plus_long_chemin(Obstacle* tab, COORDONNEE depart, COORDONNEE arrivee, int taille) {
    CHEMIN meilleur_chemin;
    meilleur_chemin.longueur = 0;

    int* visite = (int*)calloc(taille * taille, sizeof(int));
    if (!visite) return meilleur_chemin;

    COORDONNEE* chemin_courant = (COORDONNEE*)malloc(MAX_LONGUEUR_CHEMIN * sizeof(COORDONNEE));
    if (!chemin_courant) {
        free(visite);
        return meilleur_chemin;
    }

    dfs_plus_long(tab, taille, depart.x, depart.y, arrivee.x, arrivee.y, visite, chemin_courant, 0, &meilleur_chemin);

    free(visite);
    free(chemin_courant);
    return meilleur_chemin;
}

CHEMIN chemin_symetrique(const CHEMIN* chemin_fusion) {
    CHEMIN chemin_droite;
    chemin_droite.longueur = chemin_fusion->longueur;
    for (int i = 0; i < chemin_fusion->longueur; i++) {
        chemin_droite.points[i].x = TAILLE - 1 - chemin_fusion->points[i].x;
        chemin_droite.points[i].y = chemin_fusion->points[i].y;
    }
    return chemin_droite;
}




CHEMIN chemin_avec_points_intermediaire(Obstacle* tab, COORDONNEE depart, COORDONNEE arrivee, CHEMIN points_intermediaires, int taille) {
    CHEMIN chemin_final;
    chemin_final.longueur = 0;

    COORDONNEE* points = (COORDONNEE*)malloc(MAX_LONGUEUR_CHEMIN * sizeof(COORDONNEE));
    if (!points) {
        return chemin_final; 
    }

    int nb_points = points_intermediaires.longueur;
    for (int i = 0; i < nb_points; i++) {
        points[i] = points_intermediaires.points[i];
    }

    COORDONNEE courant = depart;

    CHEMIN* chemin_partiel = (CHEMIN*)malloc(sizeof(CHEMIN));
    if (!chemin_partiel) {
        free(points);
        return chemin_final;
    }

    while (nb_points > 0) {
        for (int k = 0; k < chemin_final.longueur; k++) {
            int idx = chemin_final.points[k].y * taille + chemin_final.points[k].x;
            tab[idx].lequel = 9;
        }

        COORDONNEE prochain = plus_proche(points, nb_points, courant);

        int idx = 0;
        for (int i = 0; i < nb_points; i++) {
            if (points[i].x == prochain.x && points[i].y == prochain.y) {
                idx = i;
                break;
            }
        }

        *chemin_partiel = est_accessible(tab, courant, prochain, taille);

        for (int i = (chemin_final.longueur == 0 ? 0 : 1); i < chemin_partiel->longueur; i++) {
            if (chemin_final.longueur < MAX_LONGUEUR_CHEMIN) {
                chemin_final.points[chemin_final.longueur++] = chemin_partiel->points[i];
            }
        }

        courant = prochain;
        supprimer_point(points, &nb_points, idx);
    }

    for (int k = 0; k < chemin_final.longueur; k++) {
        int idx = chemin_final.points[k].y * taille + chemin_final.points[k].x;
        tab[idx].lequel = 9;
    }

    for (int i = 0; i < taille * taille; i++) {
        if (tab[i].lequel == 5) {
            tab[i].lequel = 9;
        }
    }

    *chemin_partiel = est_accessible(tab, courant, arrivee, taille);
    if (chemin_partiel->longueur > 0) {
        for (int i = 1; i < chemin_partiel->longueur; i++) {
            if (chemin_final.longueur < MAX_LONGUEUR_CHEMIN) {
                chemin_final.points[chemin_final.longueur++] = chemin_partiel->points[i];
            }
        }
    }
    else {
        printf("Impossible de rejoindre la sortie depuis le dernier point intermédiaire !\n");
    }

    free(points);
    free(chemin_partiel);

    return chemin_final;
}







void dessiner_chemin(Obstacle* tab, CHEMIN chemin, int taille) {
    for (int i = 0; i < chemin.longueur; i++) {
        int x = chemin.points[i].x;
        int y = chemin.points[i].y;
        int idx = y * taille + x;
        if (tab[idx].lequel == 9) {
            tab[idx].lequel = 13;
        }
    }
}
