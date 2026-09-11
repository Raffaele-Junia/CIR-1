#pragma once
#include <stdlib.h>
#include <stdio.h>
#include <time.h>
#include <locale.h>
#include <stdbool.h>

typedef struct Queue {

	int* tab;
	int first;
	int last;
	int queueMaxSize;
	int queueNbElemt;

} Queue;

void NewQueue(Queue** queue, int initialQueueSize);
bool isQueueEmpty(Queue* queue);
bool isQueueFull(Queue* queue);
int queue(Queue* queue, int value);
int deQueue(Queue* queue, int* value);
void freeQueue(Queue* queue);
