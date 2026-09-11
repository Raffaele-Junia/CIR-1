#include "file.h"


void NewQueue(Queue** queue, int initialQueueSize) {
    if (initialQueueSize <= 0) {
        fprintf(stderr, "Erreur : Taille initiale doit être > 0\n");
        exit(EXIT_FAILURE);
    }
    *queue = malloc(sizeof(Queue));
    if (*queue == NULL) {
        perror("Erreur d'allocation de Queue");
        exit(EXIT_FAILURE);
    }
    (*queue)->tab = malloc(initialQueueSize * sizeof(int));
    if ((*queue)->tab == NULL) {
        free(*queue);
        perror("Erreur d'allocation du tableau");
        exit(EXIT_FAILURE);
    }
    (*queue)->first = 0;
    (*queue)->last = -1;
    (*queue)->queueMaxSize = initialQueueSize;
    (*queue)->queueNbElemt = 0;
}

bool isQueueEmpty(Queue* queue) {
    return queue->queueNbElemt == 0;
}

bool isQueueFull(Queue* queue) {
    return queue->queueMaxSize <= queue->queueNbElemt;
}

int queue(Queue* queue, int value) {
    if (isQueueFull(queue)) {
        return 0;
    }
    queue->last = (queue->last + 1) % queue->queueMaxSize;
    queue->tab[queue->last] = value;
    queue->queueNbElemt++;
    return 1;
}

int deQueue(Queue* queue, int* value) {
    if (isQueueEmpty(queue)) {
        return 0;
    }
    *value = queue->tab[queue->first];
    queue->first = (queue->first + 1) % queue->queueMaxSize;
    queue->queueNbElemt--;
    return 1;
}

void freeQueue(Queue* queue) {
    if (queue) {
        free(queue->tab);
        free(queue);
    }
}
