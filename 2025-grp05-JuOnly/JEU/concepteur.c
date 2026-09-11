#include "objets.h"  
#include <stdio.h>  
#include <stdlib.h>  
#include <windows.h>  
#include "concepteur.h"
#include "solver.h"


Obstacle* creer_tab(int taille) {
    Obstacle* tab = (Obstacle*)malloc(taille * taille * sizeof(Obstacle));
    if (tab == NULL) {
        fprintf(stderr, "Erreur d'allocation memoire\n");
        exit(EXIT_FAILURE);
    }
    return tab;
}


void remplir_mur(Obstacle* tab, int taille) {
    for (int i = 0; i < taille; i++) {
        for (int j = 0; j < taille; j++) {
            int idx = i * taille + j;
            tab[idx].lescos.x = j;
            tab[idx].lescos.y = i;
            tab[idx].lequel = 1; // 1 = Mur
        }
    }
}


void afficher_grille_entiere(Obstacle* gauche, Obstacle* droite, int taille) {
    printf("+");
    for (int i = 0; i < taille; i++) printf("-");
    printf("+");
    for (int i = 0; i < taille; i++) printf("-");
    printf("+\n");
    for (int i = 0; i < taille; i++) {
        printf("|");
        for (int j = 0; j < taille; j++) {
            printf("%c", symbole_obstacle(gauche[i * taille + j].lequel));
        }
        printf("|");
        for (int j = 0; j < taille; j++) {
            printf("%c", symbole_obstacle(droite[i * taille + j].lequel));
        }
        printf("|\n");
    }
    printf("+");
    for (int i = 0; i < taille; i++) printf("-");
    printf("+");
    for (int i = 0; i < taille; i++) printf("-");
    printf("+\n");
    printf("");
}

COORD_dep_sortie depart_aleatoire(Obstacle* gauche, Obstacle* droite, int taille) {
    COORD_dep_sortie dep;
    int ligne = rand() % taille; 


    dep.gauche.x = 0;
    dep.gauche.y = ligne;
    gauche[ligne * taille + 0].lequel = 10; 

    dep.droite.x = taille - 1;
    dep.droite.y = ligne;
    droite[ligne * taille + (taille - 1)].lequel = 11; 

    return dep;
}



COORD_dep_sortie arrivee_aleatoire(Obstacle* gauche, Obstacle* droite, int taille) {
    int ligne = rand() % taille;
    int idx_gauche = ligne * taille + (taille - 1);
    int idx_droite = ligne * taille + 0;
    gauche[idx_gauche].lequel = 8;
    droite[idx_droite].lequel = 8;

    COORD_dep_sortie sorties;
    sorties.gauche.x = taille - 1; 
    sorties.gauche.y = ligne;      
    sorties.droite.x = 0;        
    sorties.droite.y = ligne;      

    return sorties;
}



void creuser_mur(Obstacle* tab, int x, int y) {
    int idx = y * TAILLE + x;
    if (tab[idx].lequel == 1) { 
        tab[idx].lequel = 9; 
    }
}




int a_voisin_visite(int* visite, int taille, int x, int y, int px, int py) {
    COORDONNEE dirs[4] = { {1,0},{-1,0},{0,1},{0,-1} };
    for (int d = 0; d < 4; d++) {
        int nx = x + dirs[d].x;
        int ny = y + dirs[d].y;
        if (nx == px && ny == py) continue; 
        if (nx >= 0 && nx < taille && ny >= 0 && ny < taille) {
            int nidx = ny * taille + nx;
            if (visite[nidx]) return 1;
        }
    }
    return 0;
}





int chemin_aleatoire_rec(Obstacle* tab, int taille, COORDONNEE courant, COORDONNEE arrivee, int* visite, CHEMIN* chemin, int px, int py) {
    if (chemin->longueur >= MAX_LONGUEUR_CHEMIN)
        return 0;
    int idx = courant.y * taille + courant.x;
    visite[idx] = 1;
    chemin->points[chemin->longueur++] = courant;

    if (courant.x == arrivee.x && courant.y == arrivee.y)
        return 1;

    COORDONNEE dirs[4] = { {1,0},{-1,0},{0,1},{0,-1} };
    for (int i = 3; i > 0; i--) {
        int j = rand() % (i + 1);
        COORDONNEE tmp = dirs[i];
        dirs[i] = dirs[j];
        dirs[j] = tmp;
    }

    for (int d = 0; d < 4; d++) {
        int nx = courant.x + dirs[d].x;
        int ny = courant.y + dirs[d].y;
        if (nx >= 0 && nx < taille && ny >= 0 && ny < taille) {
            int nidx = ny * taille + nx;
            if (!visite[nidx] && !a_voisin_visite(visite, taille, nx, ny, courant.x, courant.y)) {
                COORDONNEE next = { nx, ny };
                if (chemin_aleatoire_rec(tab, taille, next, arrivee, visite, chemin, courant.x, courant.y))
                    return 1;
            }
        }
    }
    chemin->longueur--;
    visite[idx] = 0;
    return 0;
}



CHEMIN chemin_aleatoire(Obstacle* tab, COORDONNEE depart, COORDONNEE arrivee) {
    CHEMIN chemin;
    chemin.longueur = 0;
    int taille = TAILLE;
    int* visite = (int*)calloc(taille * taille, sizeof(int));
    srand((unsigned int)time(NULL));
    chemin_aleatoire_rec(tab, taille, depart, arrivee, visite, &chemin, -1, -1);
    free(visite);
    return chemin;
}



void elargir_passages(Obstacle* tab, int taille, int probabilite, int colonne_sortie) {
    for (int y = 1; y < taille - 1; y++) {
        for (int x = 1; x < taille - 1; x++) {
            int idx = y * taille + x;
            if (tab[idx].lequel == 9 && x != colonne_sortie) {
                if (x + 1 != colonne_sortie && (rand() % 100) < probabilite && tab[idx + 1].lequel == 1) {
                    tab[idx + 1].lequel = 9;
                }
                if ((rand() % 100) < probabilite && tab[idx + taille].lequel == 1) {
                    if ((x) != colonne_sortie) {
                        tab[idx + taille].lequel = 9;
                    }
                }
            }
        }
    }
}



void generer_labyrinthe(Obstacle* tab, int taille,int x, int y,int x_arrivee, int y_arrivee,int colonne_sortie,int* visite) {
    if (x < 0 || x >= taille || y < 0 || y >= taille)
        return;
    if (x == colonne_sortie && !(x == x_arrivee && y == y_arrivee))
        return;
    if (visite[y * taille + x])
        return;

    visite[y * taille + x] = 1;
    creuser_mur(tab, x, y);

    int dx[4] = { 0, 0, -2, 2 };
    int dy[4] = { -2, 2, 0, 0 };
    int ordre[4] = { 0, 1, 2, 3 };
    for (int i = 3; i > 0; i--) {
        int j = rand() % (i + 1);
        int tmp = ordre[i];
        ordre[i] = ordre[j];
        ordre[j] = tmp;
    }

    for (int i = 0; i < 4; i++) {
        int nx = x + dx[ordre[i]];
        int ny = y + dy[ordre[i]];
        if (nx < 0 || nx >= taille || ny < 0 || ny >= taille)
            continue;
        if (nx == colonne_sortie && !(nx == x_arrivee && ny == y_arrivee))
            continue;
        if (visite[ny * taille + nx])
            continue;

        int mx = x + dx[ordre[i]] / 2;
        int my = y + dy[ordre[i]] / 2;
        if (!(mx == colonne_sortie && !(mx == x_arrivee && my == y_arrivee))) {
            creuser_mur(tab, mx, my);
        }

        generer_labyrinthe(tab, taille, nx, ny, x_arrivee, y_arrivee, colonne_sortie, visite);
    }
}



void verifier_sortie(Obstacle* gauche, Obstacle* droite, int taille, COORDONNEE sortie_gauche, COORDONNEE sortie_droite) {
    if (sortie_gauche.x > 0) {
        int idx_sol_gauche = sortie_gauche.y * taille + (sortie_gauche.x - 1);
        if (gauche[idx_sol_gauche].lequel != 9) { 
            gauche[idx_sol_gauche].lequel = 9;
        }
    }

    // Côté droit : vérifier la case à droite de la sortie
    if (sortie_droite.x < taille - 1) {
        int idx_sol_droite = sortie_droite.y * taille + (sortie_droite.x + 1);
        if (droite[idx_sol_droite].lequel != 9) { 
            droite[idx_sol_droite].lequel = 9;
        }
    }
}

Obstacle* fusion_cotes(Obstacle* gauche, Obstacle* droite, int taille) {
    int largeur = 2 * taille + 1;
    Obstacle* fusion = creer_tab(largeur); 

    for (int y = 0; y < taille; y++) {
        for (int x = 0; x < largeur; x++) {
            int idx = y * largeur + x;
            fusion[idx].lescos.x = x;
            fusion[idx].lescos.y = y;
            if (x < taille) {
                fusion[idx] = gauche[y * taille + x];
                fusion[idx].lescos.x = x;
                fusion[idx].lescos.y = y;
            }
            else if (x == taille) {
                fusion[idx].lequel = 12; 
                fusion[idx].lescos.x = x;
                fusion[idx].lescos.y = y;
            }
            else {
                int x_droite = x - (taille + 1);
                fusion[idx] = droite[y * taille + x_droite];
                fusion[idx].lescos.x = x;
                fusion[idx].lescos.y = y;
            }
        }
    }
    return fusion;
}

char* tableau_symboles(Obstacle* fusion, int taille) {
    int largeur = 2 * taille + 1;
    char* symboles = (char*)malloc(taille * largeur * sizeof(char));
    if (!symboles) {
        fprintf(stderr, "Erreur d'allocation mémoire pour le tableau de symboles\n");
        exit(EXIT_FAILURE);
    }

    for (int y = 0; y < taille; y++) {
        for (int x = 0; x < largeur; x++) {
            int idx = y * largeur + x;
            symboles[idx] = symbole_obstacle(fusion[idx].lequel);
        }
    }
    return symboles;
}


void generer_porte_sortie(Obstacle* tab, int taille, COORDONNEE sortie) {
    int idx_sortie = sortie.y * taille + sortie.x;
    int code_porte = 5;

    if (sortie.x == taille - 1 && sortie.x - 1 >= 0) {
        int idx_porte = sortie.y * taille + (sortie.x - 1);
        tab[idx_porte].lequel = code_porte;
    }
    else if (sortie.x == 0 && sortie.x + 1 < taille) {
        int idx_porte = sortie.y * taille + (sortie.x + 1);
        tab[idx_porte].lequel = code_porte;
    }
}




void generer_cle_piece(Obstacle* gauche, Obstacle* droite, int taille, int lequel, CHEMIN chemin) {
    if (chemin.longueur == 0) return;

    int cote = rand() % 2; 

    int indices_valides[MAX_LONGUEUR_CHEMIN];
    int nb_valides = 0;

    for (int i = 0; i < chemin.longueur; ++i) {
        int x = chemin.points[i].x;
        int y = chemin.points[i].y;
        int idx_gauche = y * taille + x;
        int x_droite = taille - 1 - x;
        int idx_droite = y * taille + x_droite;

        if (cote == 0) {
            if (gauche[idx_gauche].lequel == 9 && gauche[idx_gauche].lequel != 4 && gauche[idx_gauche].lequel != 7)
                indices_valides[nb_valides++] = idx_gauche;
        }
        else {
            if (droite[idx_droite].lequel == 9 && droite[idx_droite].lequel != 4 && droite[idx_droite].lequel != 7)
                indices_valides[nb_valides++] = idx_droite;
        }
    }

    if (nb_valides == 0) return;

    int choix = rand() % nb_valides;
    int idx = indices_valides[choix];

    if (cote == 0) {
        gauche[idx].lequel = lequel;
    }
    else {
        droite[idx].lequel = lequel;
    }
}






void generer_vides(Obstacle* tab, int taille, int probabilite, CHEMIN chemin) {
    for (int y = 0; y < taille; y++) {
        for (int x = 0; x < taille; x++) {
            int idx = y * taille + x;
            if (tab[idx].lequel == 9) { 
                int sur_chemin = 0;
                for (int k = 0; k < chemin.longueur; k++) {
                    if (chemin.points[k].x == x && chemin.points[k].y == y) {
                        sur_chemin = 1;
                        break;
                    }
                }
                if (!sur_chemin && (rand() % 100) < probabilite) {
                    tab[idx].lequel = 2;
                }
            }
        }
    }
}


void aleatoire_difficulte_facile() {
    srand((unsigned int)time(NULL)); 

    Obstacle* gauche = NULL;
    Obstacle* droite = NULL;
    COORD_dep_sortie depart;
    COORD_dep_sortie arrivee;
    COORDONNEE depart_gauche, depart_droite, arrivee_gauche, arrivee_droite;
    CHEMIN* chemin_court = (CHEMIN*)malloc(sizeof(CHEMIN));
    CHEMIN* chemin_long = (CHEMIN*)malloc(sizeof(CHEMIN));

    if (!chemin_court || !chemin_long) {
        fprintf(stderr, "Erreur d'allocation mémoire pour les chemins.\n");
        free(chemin_court);
        free(chemin_long);
        return;
    }

    do {
        if (gauche) free(gauche);
        if (droite) free(droite);

        gauche = creer_tab(TAILLE);
        droite = creer_tab(TAILLE);
        remplir_mur(gauche, TAILLE);
        remplir_mur(droite, TAILLE);

        depart = depart_aleatoire(gauche, droite, TAILLE);
        arrivee = arrivee_aleatoire(gauche, droite, TAILLE);
        depart_gauche = depart.gauche;
        depart_droite = depart.droite;
        arrivee_gauche = arrivee.gauche;
        arrivee_droite = arrivee.droite;

        int* visite = (int*)calloc(TAILLE * TAILLE, sizeof(int));
        if (visite == NULL) {
            fprintf(stderr, "Erreur d'allocation memoire pour 'visite'.\n");
            free(chemin_court);
            free(chemin_long);
            return;
        }

        generer_labyrinthe(gauche, TAILLE, depart_gauche.x, depart_gauche.y, arrivee_gauche.x, arrivee_gauche.y, arrivee_gauche.x, visite);
        elargir_passages(gauche, TAILLE, 30, arrivee_gauche.x);
        memset(visite, 0, TAILLE * TAILLE * sizeof(int));
        generer_labyrinthe(droite, TAILLE, depart_droite.x, depart_droite.y, arrivee_droite.x, arrivee_droite.y, arrivee_droite.x, visite);
        elargir_passages(droite, TAILLE, 30, arrivee_droite.x);

        for (int y = 0; y < TAILLE; y++) {
            if (y == arrivee_droite.y) continue;
            if ((rand() % 100) < 40 && droite[y * TAILLE + 1].lequel == 1) {
                droite[y * TAILLE + 1].lequel = 9;
            }
        }

        free(visite);

        Obstacle* tabinv = creer_tab(TAILLE);
        for (int i = 0; i < TAILLE * TAILLE; i++) tabinv[i] = droite[i];
        inverser_colonnes(tabinv);
        Obstacle* resultat = fusionner_grilles(gauche, tabinv);

        *chemin_court = est_accessible(resultat, depart_gauche, arrivee_gauche, TAILLE);
        *chemin_long = plus_long_chemin(resultat, depart_gauche, arrivee_gauche, TAILLE);
        free(tabinv);
        free(resultat);

    } while (chemin_court->longueur == 0 || chemin_court->longueur < 40);

    printf("Chemin le plus court : %d\n", chemin_court->longueur);
    printf("Chemin le plus long : %d\n", chemin_long->longueur);

    dessiner_chemin(gauche, *chemin_court, TAILLE);
    CHEMIN chemin_sym = chemin_symetrique(chemin_court);
    dessiner_chemin(droite, chemin_sym, TAILLE);

    afficher_grille_entiere(gauche, droite, TAILLE);

    Obstacle* fusion = fusion_cotes(gauche, droite, TAILLE);
    char* symboles = tableau_symboles(fusion, TAILLE);
    FILE* fichier = fopen("../SITE/file/aleatoire.txt", "w");
    if (fichier == NULL) {
        perror("Erreur d'ouverture du fichier");
        free(chemin_court);
        free(chemin_long);
        return;
    }

    int largeur = 2 * TAILLE + 1;
    for (int y = 0; y < TAILLE; y++) {
        for (int x = 0; x < largeur; x++) {
            fputc(symboles[y * largeur + x], fichier);
        }
        fputc('\n', fichier);
    }

    fclose(fichier);
    free(symboles);
    free(fusion);

    free(gauche);
    free(droite);
    free(chemin_court);
    free(chemin_long);
}







void aleatoire_difficulte_moyenne() {
    srand((unsigned int)time(NULL));  

    Obstacle* gauche = NULL;
    Obstacle* droite = NULL;
    COORD_dep_sortie depart;
    COORD_dep_sortie arrivee;
    COORDONNEE depart_gauche, depart_droite, arrivee_gauche, arrivee_droite;
    CHEMIN* chemin_court = (CHEMIN*)malloc(sizeof(CHEMIN));
    CHEMIN* chemin_long = (CHEMIN*)malloc(sizeof(CHEMIN));
    CHEMIN* points_intermediaire = (CHEMIN*)malloc(sizeof(CHEMIN));

    if (!chemin_court || !chemin_long || !points_intermediaire) {
        fprintf(stderr, "Erreur d'allocation mémoire pour les chemins.\n");
        free(chemin_court);
        free(chemin_long);
        free(points_intermediaire);
        return;
    }

    do {
        if (gauche) free(gauche);
        if (droite) free(droite);

        gauche = creer_tab(TAILLE);
        droite = creer_tab(TAILLE);
        remplir_mur(gauche, TAILLE);
        remplir_mur(droite, TAILLE);

        depart = depart_aleatoire(gauche, droite, TAILLE);
        arrivee = arrivee_aleatoire(gauche, droite, TAILLE);
        depart_gauche = depart.gauche;
        depart_droite = depart.droite;
        arrivee_gauche = arrivee.gauche;
        arrivee_droite = arrivee.droite;

        int* visite = (int*)calloc(TAILLE * TAILLE, sizeof(int));
        if (visite == NULL) {
            fprintf(stderr, "Erreur d'allocation memoire pour 'visite'.\n");
            free(chemin_court);
            free(chemin_long);
            free(points_intermediaire);
            return;
        }

        generer_labyrinthe(gauche, TAILLE, depart_gauche.x, depart_gauche.y, arrivee_gauche.x, arrivee_gauche.y, arrivee_gauche.x, visite);
        elargir_passages(gauche, TAILLE, 30, arrivee_gauche.x);
        memset(visite, 0, TAILLE * TAILLE * sizeof(int));
        generer_labyrinthe(droite, TAILLE, depart_droite.x, depart_droite.y, arrivee_droite.x, arrivee_droite.y, arrivee_droite.x, visite);
        elargir_passages(droite, TAILLE, 30, arrivee_droite.x);

        for (int y = 0; y < TAILLE; y++) {
            if (y == arrivee_droite.y) continue;
            if ((rand() % 100) < 40 && droite[y * TAILLE + 1].lequel == 1) {
                droite[y * TAILLE + 1].lequel = 9;
            }
        }

        free(visite);

        Obstacle* tabinv = creer_tab(TAILLE);
        for (int i = 0; i < TAILLE * TAILLE; i++) tabinv[i] = droite[i];
        inverser_colonnes(tabinv);
        Obstacle* resultat = fusionner_grilles(gauche, tabinv);

        *chemin_court = est_accessible(resultat, depart_gauche, arrivee_gauche, TAILLE);
        *chemin_long = plus_long_chemin(resultat, depart_gauche, arrivee_gauche, TAILLE);
        free(tabinv);
        free(resultat);

    } while (chemin_court->longueur == 0 || chemin_court->longueur < 40);

    printf("Chemin le plus court : %d\n", chemin_court->longueur);
    printf("Chemin le plus long : %d\n", chemin_long->longueur);

    generer_porte_sortie(gauche, TAILLE, arrivee_gauche);
    generer_porte_sortie(droite, TAILLE, arrivee_droite);

    afficher_grille_entiere(gauche, droite, TAILLE);

    Obstacle* tabinv = creer_tab(TAILLE);
    for (int i = 0; i < TAILLE * TAILLE; i++) tabinv[i] = droite[i];
    inverser_colonnes(tabinv);

    Obstacle* resultat = fusionner_grilles(gauche, tabinv);

    generer_cle_piece(gauche, droite, TAILLE, 4, *chemin_long);
    generer_cle_piece(gauche, droite, TAILLE, 4, *chemin_long);
    generer_cle_piece(gauche, droite, TAILLE, 7, *chemin_long);

	free(tabinv);
	free(resultat);

    tabinv = creer_tab(TAILLE);
    for (int i = 0; i < TAILLE * TAILLE; i++) tabinv[i] = droite[i];
    inverser_colonnes(tabinv);

    resultat = fusionner_grilles(gauche, tabinv);

    *points_intermediaire = points_intermediaires(resultat, TAILLE);
	printf("Nombre de points intermediaires : %d\n", points_intermediaire->longueur);
    *chemin_court = chemin_avec_points_intermediaire(resultat, depart_gauche, arrivee_gauche, *points_intermediaire, TAILLE);
	printf("%d\n", chemin_court->longueur);
    dessiner_chemin(gauche, *chemin_court, TAILLE);
    CHEMIN chemin_sym = chemin_symetrique(chemin_court);
    dessiner_chemin(droite, chemin_sym, TAILLE);

    afficher_grille_entiere(gauche, droite, TAILLE);

    Obstacle* fusion = fusion_cotes(gauche, droite, TAILLE);
    char* symboles = tableau_symboles(fusion, TAILLE);
    FILE* fichier = fopen("../SITE/file/aleatoiredeux.txt", "w");
    if (fichier == NULL) {
        perror("Erreur d'ouverture du fichier");
        free(chemin_court);
        free(chemin_long);
        free(points_intermediaire);
        free(tabinv);
        free(resultat);
        return;
    }

    int largeur = 2 * TAILLE + 1;
    for (int y = 0; y < TAILLE; y++) {
        for (int x = 0; x < largeur; x++) {
            fputc(symboles[y * largeur + x], fichier);
        }
        fputc('\n', fichier);
    }

    fclose(fichier);
    free(symboles);
    free(fusion);

    free(gauche);
    free(droite);
    free(tabinv);
    free(resultat);
    free(chemin_court);
    free(chemin_long);
    free(points_intermediaire);
}



void aleatoire_difficulte_max() {
    srand((unsigned int)time(NULL));

    Obstacle* gauche = NULL;
    Obstacle* droite = NULL;
    COORD_dep_sortie depart;
    COORD_dep_sortie arrivee;
    COORDONNEE depart_gauche, depart_droite, arrivee_gauche, arrivee_droite;
    CHEMIN* chemin_court = (CHEMIN*)malloc(sizeof(CHEMIN));
    CHEMIN* chemin_long = (CHEMIN*)malloc(sizeof(CHEMIN));
    CHEMIN* chemin_long_droite = (CHEMIN*)malloc(sizeof(CHEMIN));
    CHEMIN* points_intermediaire = (CHEMIN*)malloc(sizeof(CHEMIN));

    if (!chemin_court || !chemin_long || !chemin_long_droite || !points_intermediaire) {
        fprintf(stderr, "Erreur d'allocation mémoire pour les chemins.\n");
        free(chemin_court);
        free(chemin_long);
        free(chemin_long_droite);
		free(points_intermediaire);
        return;
    }

    do {
        if (gauche) free(gauche);
        if (droite) free(droite);

        gauche = creer_tab(TAILLE);
        droite = creer_tab(TAILLE);
        remplir_mur(gauche, TAILLE);
        remplir_mur(droite, TAILLE);

        depart = depart_aleatoire(gauche, droite, TAILLE);
        arrivee = arrivee_aleatoire(gauche, droite, TAILLE);
        depart_gauche = depart.gauche;
        depart_droite = depart.droite;
        arrivee_gauche = arrivee.gauche;
        arrivee_droite = arrivee.droite;

        int* visite = (int*)calloc(TAILLE * TAILLE, sizeof(int));
        if (visite == NULL) {
            fprintf(stderr, "Erreur d'allocation memoire pour 'visite'.\n");
            free(chemin_court);
            free(chemin_long);
            free(chemin_long_droite);
            return;
        }

        generer_labyrinthe(gauche, TAILLE, depart_gauche.x, depart_gauche.y, arrivee_gauche.x, arrivee_gauche.y, arrivee_gauche.x, visite);
        elargir_passages(gauche, TAILLE, 30, arrivee_gauche.x);
        memset(visite, 0, TAILLE * TAILLE * sizeof(int));
        generer_labyrinthe(droite, TAILLE, depart_droite.x, depart_droite.y, arrivee_droite.x, arrivee_droite.y, arrivee_droite.x, visite);
        elargir_passages(droite, TAILLE, 30, arrivee_droite.x);

        for (int y = 0; y < TAILLE; y++) {
            if (y == arrivee_droite.y) continue;
            if ((rand() % 100) < 40 && droite[y * TAILLE + 1].lequel == 1) {
                droite[y * TAILLE + 1].lequel = 9;
            }
        }

        free(visite);

        Obstacle* tabinv = creer_tab(TAILLE);
        for (int i = 0; i < TAILLE * TAILLE; i++) tabinv[i] = droite[i];
        inverser_colonnes(tabinv);
        Obstacle* resultat = fusionner_grilles(gauche, tabinv);

        *chemin_court = est_accessible(resultat, depart_gauche, arrivee_gauche, TAILLE);
        *chemin_long = plus_long_chemin(resultat, depart_gauche, arrivee_gauche, TAILLE);
        free(tabinv);
        free(resultat);

    } while (chemin_court->longueur == 0 || chemin_court->longueur < 40);

    printf("Chemin le plus court : %d\n", chemin_court->longueur);
    printf("Chemin le plus long : %d\n", chemin_long->longueur);

    *chemin_long_droite = chemin_symetrique(chemin_long);
    for (int k = 0; k < chemin_long->longueur; k++) {
        int x = chemin_long->points[k].x;
        int y = chemin_long->points[k].y;
        gauche[y * TAILLE + x].lequel = 9;
        droite[y * TAILLE + (TAILLE - 1 - x)].lequel = 9;
    }
    gauche[depart_gauche.y * TAILLE + depart_gauche.x].lequel = 10; 
    droite[depart_droite.y * TAILLE + depart_droite.x].lequel = 11; 
    gauche[arrivee_gauche.y * TAILLE + arrivee_gauche.x].lequel = 8; 
    droite[arrivee_droite.y * TAILLE + arrivee_droite.x].lequel = 8;

    generer_porte_sortie(gauche, TAILLE, arrivee_gauche);
    generer_porte_sortie(droite, TAILLE, arrivee_droite);

    generer_vides(gauche, TAILLE, 20, *chemin_long);
    generer_vides(droite, TAILLE, 20, *chemin_long_droite);

    afficher_grille_entiere(gauche, droite, TAILLE);

    Obstacle* tabinv = creer_tab(TAILLE);
    for (int i = 0; i < TAILLE * TAILLE; i++) tabinv[i] = droite[i];
    inverser_colonnes(tabinv);

    Obstacle* resultat = fusionner_grilles(gauche, tabinv);

    generer_cle_piece(gauche, droite, TAILLE, 4, *chemin_long);
    generer_cle_piece(gauche, droite, TAILLE, 4, *chemin_long);
    generer_cle_piece(gauche, droite, TAILLE, 7, *chemin_long);

    free(tabinv);
    free(resultat);

    tabinv = creer_tab(TAILLE);
    for (int i = 0; i < TAILLE * TAILLE; i++) tabinv[i] = droite[i];
    inverser_colonnes(tabinv);

    resultat = fusionner_grilles(gauche, tabinv);

    *points_intermediaire = points_intermediaires(resultat, TAILLE);
    printf("Nombre de points intermediaires : %d\n", points_intermediaire->longueur);
    *chemin_court = chemin_avec_points_intermediaire(resultat, depart_gauche, arrivee_gauche, *points_intermediaire, TAILLE);
    printf("%d\n", chemin_court->longueur);
    dessiner_chemin(gauche, *chemin_court, TAILLE);
    CHEMIN chemin_sym = chemin_symetrique(chemin_court);
    dessiner_chemin(droite, chemin_sym, TAILLE);

    afficher_grille_entiere(gauche, droite, TAILLE);

    Obstacle* fusion = fusion_cotes(gauche, droite, TAILLE);
    char* symboles = tableau_symboles(fusion, TAILLE);
    FILE* fichier = fopen("../SITE/file/aleatoiretrois.txt", "w");
    if (fichier == NULL) {
        perror("Erreur d'ouverture du fichier");
        free(chemin_court);
        free(chemin_long);
        free(chemin_long_droite);
        free(tabinv);
        free(resultat);
        return;
    }

    int largeur = 2 * TAILLE + 1;
    for (int y = 0; y < TAILLE; y++) {
        for (int x = 0; x < largeur; x++) {
            fputc(symboles[y * largeur + x], fichier);
        }
        fputc('\n', fichier);
    }

    fclose(fichier);
    free(symboles);
    free(fusion);

    free(gauche);
    free(droite);
    free(tabinv);
    free(resultat);
    free(chemin_court);
    free(chemin_long);
    free(chemin_long_droite);
}

