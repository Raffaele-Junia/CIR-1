#include <stdio.h>
#include "grille.h"

void afficherGrilleVide(void) {
    printf("+");
    for (int i = 0; i < COLONNES; i++) {
        if (i == COLONNES / 2) printf("+");
        printf("-");
    }
    printf("+\n");

    for (int i = 0; i < LIGNES; i++) {
        printf("|");
        for (int j = 0; j < COLONNES; j++) {
            if (j == COLONNES / 2) printf("|");
            printf(" ");
        }
        printf("|\n");
    }

    printf("+");
    for (int i = 0; i < COLONNES; i++) {
        if (i == COLONNES / 2) printf("+");
        printf("-");
    }
    printf("+\n");
}
