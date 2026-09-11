#include "objets.h"
#include <stdio.h>
#include <stdlib.h>

#ifdef _WIN32
    #include <windows.h>
#else
    #include <unistd.h>
    #include <termios.h>
#endif

Player player = { {2, 13}, 0, 0 };
Obstacle obstacles[1000];
int nb_obstacles = 0;
int niveau_actuel = 0;

void move_cursor(int x, int y) {
#ifdef _WIN32
    COORD pos;
    pos.X = x;
    pos.Y = y;
    SetConsoleCursorPosition(GetStdHandle(STD_OUTPUT_HANDLE), pos);
#else
    printf("\033[%d;%dH", y + 1, x + 1);
    fflush(stdout);
#endif
}

void clear_screen() {
#ifdef _WIN32
    system("cls");
#else
    printf("\033[2J\033[H");
    fflush(stdout);
#endif
}
Obstacle creer_obstacle(int x, int y, int type) {
    Obstacle obj;
    obj.lescos.x = x;
    obj.lescos.y = y;
    obj.lequel = type;

    for (int i = 0; i < nb_obstacles; i++) {
        if (obstacles[i].lescos.x == x && obstacles[i].lescos.y == y) {
            obstacles[i].lequel = type;
            return obj;
        }
    }

    if (nb_obstacles < 1000) {
        obstacles[nb_obstacles] = obj;
        nb_obstacles++;
    }

    return obj;
}

void reinitialiser_jeu() {
    nb_obstacles = 0;
    player.lescos.x = 0;
    player.lescos.y = 0;
    player.cle = 0;
    player.pieces = 0;
    clear_screen();
}

char symbole_obstacle(int lequel) {
    switch (lequel) {
        case 1: return '#';
        case 2: return ' ';
        case 3: return 'T';
        case 4: return 'C';
        case 5: return 'P';
        case 6: return 'D';
        case 7: return '$';
        case 8: return 'S';
        case 9: return 'B';
        case 10: return 'O';
        case 11: return 'Q';
        case 12: return '|';
        case 13: return 'W';// chemin
        default: return '?';
    }
}

void afficher_obstacle(const Obstacle* obs) {
    int x = obs->lescos.x;
    int y = obs->lescos.y;

    if (x == 16) return;
    if (x < 1 || x >= 32 || y < 1 || y >= 16) return;

    move_cursor(x, y);
    putchar(symbole_obstacle(obs->lequel));
}
