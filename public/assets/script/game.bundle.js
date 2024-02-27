function animations(scene) {
    scene.anims.create({
        key: 'explode',
        frames: [ 
            { key: 'explosion1' },
            { key: 'explosion2' },
            { key: 'explosion3' },
            { key: 'explosion4' },
            { key: 'explosion5' },
            { key: 'explosion6' },
            { key: 'explosion7' },
            { key: 'explosion8' },
            { key: 'explosion9' }
        ],
        frameRate: '30',
        repeat: '0'
    });

    scene.anims.create({
        key: 'explode2',
        frames: [
            { key: 'explosions1' },
            { key: 'explosions2' },
            { key: 'explosions3' },
            { key: 'explosions4' },
            { key: 'explosions5' },
            { key: 'explosions6' },
            { key: 'explosions7' },
            { key: 'explosions8' },
            { key: 'explosions9' },
            { key: 'explosions10' },
            { key: 'explosions11' },
            { key: 'explosions12' },
            { key: 'explosions13' }
        ],
        frameRate: '30',
        repeat: '0'
    });
}

// Класс, представляющий конфету на игровом поле.
class Candy {
    /**
     * Создает экземпляр конфеты.
     * @param {Phaser.Scene} scene - Сцена, к которой принадлежит конфета.
     * @param {number} x - Горизонтальная позиция конфеты на сетке.
     * @param {number} y - Вертикальная позиция конфеты на сетке.
     * @param {number} color - Цвет конфеты.
     */

    constructor(scene, x, y, color) {
        this.scene = scene;
        this.x = x;
        this.y = y;
        this.color = color;
        this.grid = scene.grid; // Сохранение ссылки на grid
        this.gridHeight = scene.levelData.tiles.length;
        this.gridWidth = scene.levelData.tiles[0].length; // Предполагаем, что все строки имеют одинаковую длину
        this.СandySpriteSize = scene.СandySpriteSize; // Размер конфеты
        this.score = scene.score;
        this.unsuccessfulSwipes = 0;
    }


    /**
     * Создает спрайт конфеты.
     */
    createCandySprite() {
        // Сначала создаем спрайт
        this.sprite = this.scene.add.sprite(0, 0, this.color).setScale(this.СandySpriteSize);
        this.sprite.setDepth(3); // Example depth value
        if (this.isFrozen) {
            this.ice = this.scene.add.sprite(this.x * this.scene.СandySize + this.scene.offsetX, this.y * this.scene.СandySize + this.scene.offsetY, 'ice').setScale(this.СandySpriteSize + .1);
            this.ice.setDepth(4);
        }

        // Затем используем его свойства для установки позиции
        this.sprite.x = this.x * this.scene.СandySize + this.scene.offsetX;
        this.sprite.y = this.y * this.scene.СandySize + this.scene.offsetY;

    }

    /**
     * Включает возможность перетаскивания конфеты.
     */
    enableSwipe() {
        this.sprite.setInteractive();

        this.sprite.on('pointerdown', (pointer) => {
            this.scene.activeCandy = this; // Установка активной конфеты
            this.isDragging = true;
            this.startDragPos = { x: pointer.worldX, y: pointer.worldY };
        });

        this.scene.input.on('pointerup', (pointer) => {
            if (this.scene.activeCandy && this.scene.activeCandy.isDragging) {
                // Обработка свайпа
                const endDragPos = { x: pointer.worldX, y: pointer.worldY };
                this.scene.activeCandy.handleSwipe(endDragPos, this.scene);

                // Сброс состояния
                this.scene.activeCandy.isDragging = false;
                this.scene.activeCandy = null;
            }

        });
    }

    /**
     * Обрабатывает действие перетаскивания конфеты.
     * @param {Object} endDragPos - Координаты конца перетаскивания.
     * @param {Phaser.Scene} scene - Сцена, в которой происходит действие.
     */
    handleSwipe(endDragPos, scene) {
        const swipeThreshold = 50; // Минимальное расстояние для свайпа
        const dx = endDragPos.x - this.startDragPos.x;
        const dy = endDragPos.y - this.startDragPos.y;

        let swapWith = null; // Конфета для обмена

        if (Math.abs(dx) > swipeThreshold || Math.abs(dy) > swipeThreshold) {
            // Проверяем, находится ли конфета в пределах сетки
            if (this.x < 0 || this.x >= this.gridWidth || this.y < 0 || this.y >= this.gridHeight) {
                if (loggingEnabled) {
                    console.log("Свайп за пределами сетки");
                }
                return;
            }
            if (Math.abs(dx) > Math.abs(dy)) {
                // Горизонтальный свайп
                if (dx > 0 && this.x < this.gridWidth - 1) {
                    swapWith = this.grid[this.y][this.x + 1]; // Свайп вправо
                } else if (this.x > 0) {
                    swapWith = this.grid[this.y][this.x - 1]; // Свайп влево
                }
            } else {
                // Вертикальный свайп
                if (dy > 0 && this.y < this.gridHeight - 1) {
                    swapWith = this.grid[this.y + 1][this.x]; // Свайп вниз
                } else if (this.y > 0) {
                    swapWith = this.grid[this.y - 1][this.x]; // Свайп вверх
                }
            }
        }

        if (swapWith) {
            if (swapWith.isFrozen) {
                if (loggingEnabled) {
                    console.log("Замороженная конфета");
                }
                return; // Прекращаем обработку свайпа, если это бомба
            }

            // Добавляем анимацию перемещения для обеих конфет
            scene.tweens.add({
                targets: this.sprite,
                x: swapWith.x * this.scene.СandySize + this.scene.offsetX,
                y: swapWith.y * this.scene.СandySize + this.scene.offsetY,
                duration: 100,
                ease: 'Sine.easeInOut',
                onComplete: () => {
                    // Меняем местами конфеты // Обновляем позиции в классах Candy
                    [this.grid[this.y][this.x], this.grid[swapWith.y][swapWith.x]] = [this.grid[swapWith.y][swapWith.x], this.grid[this.y][this.x]];
                    [this.x, this.y, swapWith.x, swapWith.y] = [swapWith.x, swapWith.y, this.x, this.y];

                    if (this.hasExploded !== undefined) {
                        // Вызываем метод explodeBomb из экземпляра BombCandy
                        this.explodeBomb();
                        updateMovesUI(scene, 'minus'); // Уменьшаем количество шагов
                        return;
                    } else if (swapWith.hasExploded !== undefined) {
                        swapWith.explodeBomb();
                        updateMovesUI(scene, 'minus'); // Уменьшаем количество шагов
                        return;
                    }

                    if (this.hasLightning !== undefined) {
                        // Вызываем метод explodeBomb из экземпляра BombCandy
                        this.explodeCandy(swapWith.color);
                        updateMovesUI(scene, 'minus'); // Уменьшаем количество шагов

                        return;
                    } else if (swapWith.hasLightning !== undefined) {
                        swapWith.explodeCandy(this.color);
                        updateMovesUI(scene, 'minus'); // Уменьшаем количество шагов

                        return;
                    }

                    if (this.rocketType !== undefined) {
                        // Проверяем, является ли конфета, с которой происходит обмен, также ракетой
                        if (swapWith.rocketType !== undefined) {
                            // Если обе конфеты - ракеты, вызываем взрыв по кресту
                            this.explodeCrossRocket();
                            swapWith.explodeCrossRocket();
                            updateMovesUI(scene, 'minus'); // Уменьшаем количество шагов
                        } else {
                            // Если конфета, с которой происходит обмен, не ракета, вызываем обычный взрыв
                            this.explodeRocket();
                            updateMovesUI(scene, 'minus'); // Уменьшаем количество шагов
                        }
                        return;
                    } else if (swapWith.rocketType !== undefined) {
                        if (this.rocketType == undefined) {
                            swapWith.explodeRocket();
                            updateMovesUI(scene, 'minus'); // Уменьшаем количество шагов

                        }
                        return;
                    }


                    let matches = findMatches(this.scene);

                    if (matches.length === 0) {
                        // // Возврат конфет на их исходные позиции
                        [this.grid[this.y][this.x], this.grid[swapWith.y][swapWith.x]] = [this.grid[swapWith.y][swapWith.x], this.grid[this.y][this.x]];
                        [this.x, this.y, swapWith.x, swapWith.y] = [swapWith.x, swapWith.y, this.x, this.y];

                        scene.tweens.add({
                            targets: this.sprite,
                            x: this.x * this.scene.СandySize + this.scene.offsetX,
                            y: this.y * this.scene.СandySize + this.scene.offsetY,
                            duration: 80,
                            delay: 120,
                            ease: 'Sine.easeInOut',
                        });

                        scene.tweens.add({
                            targets: swapWith.sprite,
                            x: swapWith.x * this.scene.СandySize + this.scene.offsetX,
                            y: swapWith.y * this.scene.СandySize + this.scene.offsetY,
                            duration: 80,
                            delay: 120,
                            ease: 'Sine.easeInOut',
                        });

                        this.unsuccessfulSwipes++;

                        if (this.unsuccessfulSwipes >= 5) {
                            showHint(scene);
                            this.unsuccessfulSwipes = 0; // Сбросить счетчик после показа подсказки
                        }
                    } else {
                        updateMovesUI(scene, 'minus'); // Уменьшаем количество шагов
                        icinstantGift(scene);

                        removeMatches(matches, scene); // Удаляем совпадения
                    }

                    // Сброс состояния
                    this.isDragging = false;
                    this.startDragPos = null;
                }
            });

            scene.tweens.add({
                targets: swapWith.sprite,
                x: this.x * this.scene.СandySize + this.scene.offsetX,
                y: this.y * this.scene.СandySize + this.scene.offsetY,
                duration: 100,
                ease: 'Sine.easeInOut',
            });
        }


    }
}


class BombCandy extends Candy {
    constructor(scene, x, y) {
        super(scene, x, y, 'bomb');
        this.hasExploded = false;
        this.createCandySprite();
    }

    createCandySprite() {
        super.createCandySprite();
        this.sprite.setTexture('bomb');
        this.sprite.setScale(0.1);

        this.scene.tweens.add({
            targets: this.sprite,
            scaleX: this.scene.СandySpriteSize, // конечный масштаб по X
            scaleY: this.scene.СandySpriteSize, // конечный масштаб по Y
            ease: 'Back.easeOut', // тип анимации, можно выбрать другой
            duration: 500, // продолжительность анимации в миллисекундах
            delay: 300,
        });
    }

    explodeBomb() {
        if (this.hasExploded) return; // Если эта бомба уже взорвана, выходим из функции
        this.hasExploded = true; // Устанавливаем флаг, что эта бомба уже взорвана
        let bombX = this.x;
        let bombY = this.y;
        const delayBetweenExplosions = 30;
        this.scene.score += 150;


        for (let x = bombX - 1; x <= bombX + 1; x++) {
            for (let y = bombY - 1; y <= bombY + 1; y++) {
                if (x >= 0 && x < this.gridWidth && y >= 0 && y < this.gridHeight && this.grid[y][x]) {
                    if (this.grid[y][x] instanceof FrozenCandy) {
                        // Особая обработка только для взрыва ракеты
                        checkAndUnfreezeCandy(x, y, this.scene.levelData, this.scene.grid);
                        return;
                    }
                    this.scene.time.delayedCall(delayBetweenExplosions * x, () => {

                        // Создание спрайта анимации взрыва в позиции бомбы
                        let explosion = this.scene.add.sprite(x * this.scene.СandySize + this.scene.offsetX, y * this.scene.СandySize + this.scene.offsetY, 'explosion2').setScale(this.scene.explosionSpriteSize);
                        explosion.setDepth(10);
                        explosion.play('explode2');

                        // Уничтожение спрайта анимации после завершения
                        explosion.on('animationcomplete', () => {
                            explosion.destroy(); // or explosion.setVisible(false);
                        });
                    });
                    playDisappearAnimation(this.grid[y][x].sprite, () => {
                        this.scene.score += 15;
                    });

                    // Проверяем, является ли конфета бомбой или ракетой и вызываем соответствующий метод
                    if (this.grid[y][x] instanceof BombCandy) {
                        this.grid[y][x].explodeBomb(); // Здесь нужно определить логику взрыва бомбы
                    } else if (this.grid[y][x] instanceof RocketCandy) {
                        this.grid[y][x].explodeRocket(); // Рекурсивный вызов взрыва ракеты
                    } else {
                        if (targetCandies[this.grid[y][x].color] && targetCandies[this.grid[y][x].color].collected < targetCandies[this.grid[y][x].color].required) {
                            targetCandies[this.grid[y][x].color].collected++;
                        }
                    }
                    if (this.grid[y][x] !== null && 'color' in this.grid[y][x]) {
                        if (this.grid[y][x].color === 'candy5') {
                            updateMovesUI(this.scene, 'plus'); // Увеличиваем количество шагов
                        }
                    }


                    this.grid[y][x] = null;
                }
            }
            updateCollectedCandyUI(this.scene);
            checkLevelCompletion(this.scene);
        }

        // Создание анимации взрыва в текущей позиции бомбы
        let explosion1 = this.scene.add.sprite(this.sprite.x, this.sprite.y, 'explosion1').setScale(this.scene.explosionSpriteSize);
        explosion1.setDepth(10);

        explosion1.play('explode');

        explosion1.on('animationcomplete', () => {
            explosion1.destroy();
            shiftCandiesDown(this.scene);
        });


    }

}

// Функция для создания бомбы
function createBomb(x, y, scene) {
    const bombCandy = new BombCandy(scene, x, y);
    bombCandy.enableSwipe();
    scene.grid[y][x] = bombCandy;

    if (loggingEnabled) {
        console.log(`Бомба создана в позиции (${x}, ${y})`); // Лог для проверки создания бомбы
    }
}

class Button extends Phaser.GameObjects.Container {
    constructor(scene, x, y, text, callback) {
        super(scene, x, y);

        this.scene = scene;
        this.x = x;
        this.y = y;
        this.text = text;
        this.callback = callback;

        // Создаем фон кнопки
        this.background = this.scene.add.graphics();
        this.background.fillStyle(0xEA2C86, 1);
        this.background.fillRoundedRect(-276, -54, 552, 108, 50); // Координаты относительно центра контейнера

        this.add(this.background);

        // Создаем текст кнопки
        this.textObject = this.scene.add.text(0, 0, text, { fontSize: '40px', fill: '#fff', fontFamily: 'AVENGEANCE',});
        this.textObject.setOrigin(0.5, 0.5);
        this.textObject.setShadow(2, 2, '#9D1555', 2, true, true);
        this.add(this.textObject);

        // Включаем взаимодействие с кнопкой

        this.setInteractive(new Phaser.Geom.Rectangle(-276, -54, 552, 108), Phaser.Geom.Rectangle.Contains);
        this.on('pointerdown', this.onPointerDown, this);
        this.on('pointerup', this.onPointerUp, this);

        this.scene.add.existing(this);
    }

    onPointerDown() {
        this.background.clear();
        this.background.fillStyle(0xBB236B, 1);
        this.background.fillRoundedRect(-276, -54, 552, 108, 50);
    }

    onPointerUp() {
        this.background.clear();
        this.background.fillStyle(0xEA2C86, 1);
        this.background.fillRoundedRect(-276, -54, 552, 108, 50);
        this.callback();

    }
}

class ButtonBorder extends Button {
    constructor(scene, x, y, text, callback) {
        super(scene, x, y);
        this.callback = callback;

        this.background.clear();
        this.background.lineStyle(8, 0xFFFFFF, 1); // Обводка (ширина, цвет, прозрачность)
        this.background.strokeRoundedRect(-276, -54, 552, 108, 50); // Обводка
        // Создаем фон кнопки
        
        this.add(this.background);

        // Создаем текст кнопки
        this.textObject = this.scene.add.text(0, 0, text, { fontSize: '40px', fill: '#fff', fontFamily: 'AVENGEANCE',});
        this.textObject.setOrigin(0.5, 0.5);
        this.add(this.textObject);

        // Включаем взаимодействие с кнопкой

        this.setInteractive(new Phaser.Geom.Rectangle(-276, -54, 552, 108), Phaser.Geom.Rectangle.Contains);
        this.on('pointerdown', this.onPointerDown, this);
        this.on('pointerup', this.onPointerUp, this);

        this.scene.add.existing(this);
    }

    onPointerDown() {
        this.background.clear();
        this.background.fillStyle(0xFFFFFF, .2);
        this.background.fillRoundedRect(-276, -54, 552, 108, 50);
        this.background.lineStyle(8, 0xFFFFFF, 1); // Обводка (ширина, цвет, прозрачность)
        this.background.strokeRoundedRect(-276, -54, 552, 108, 50); // Обводка
    }

    onPointerUp() {
        this.background.clear();
        this.background.lineStyle(8, 0xFFFFFF, 1); // Обводка (ширина, цвет, прозрачность)
        this.background.strokeRoundedRect(-276, -54, 552, 108, 50); // Обводка
        this.callback();
    }
}


// Проверяем соседей слева и сверху, так как новые конфеты заполняют сетку сверху вниз
function checkForImmediateMatches(grid, x, y, color) {
    if (x > 1 && grid[y][x - 1] && grid[y][x - 1].color === color && grid[y][x - 2] && grid[y][x - 2].color === color) {
        return true; // Горизонтальное совпадение
    }

    if (y > 1 && grid[y - 1][x] && grid[y - 1][x].color === color && grid[y - 2][x] && grid[y - 2][x].color === color) {
        return true; // Вертикальное совпадение
    }
    return false;
}

/**
 * Генерирует новые конфеты для заполнения пустых мест на игровом поле.
 * @param {Phaser.Scene} scene - Сцена, в которой генерируются новые конфеты.
 */
function generateNewCandies(scene) {
    const baseDuration = 100; // Базовая продолжительность анимации
    const durationPerCell = 40; // Дополнительная продолжительность анимации на каждую ячейку
    const gridHeight = scene.levelData.tiles.length;
    const gridWidth = scene.levelData.tiles[0].length; // Предполагаем, что все строки имеют одинаковую длину

    for (let x = 0; x < gridWidth; x++) {
        for (let y = 0; y < gridHeight; y++) {
            if (scene.grid[y][x] === null && scene.levelData.tiles[y][x] > 0) {
                // Определение продолжительности анимации на основе высоты падения
                let fallHeight = y; // Высота падения в ячейках
                let duration = baseDuration + durationPerCell * fallHeight; // Общая продолжительность анимации

                // Остальной код для генерации конфеты
                let color, isMatch;
                do {
                    color = getRandomColor(scene.levelData, scene.levelNumber);
                    isMatch = checkForImmediateMatches(scene.grid, x, y, color);
                } while (isMatch);

                const newCandy = new Candy(scene, x, y, color);
                newCandy.createCandySprite();
                newCandy.enableSwipe();
                scene.grid[y][x] = newCandy;
                activeTweenCount++;

                newCandy.sprite.y = -scene.СandySize + scene.offsetY;
                scene.tweens.add({
                    targets: newCandy.sprite,
                    y: y * scene.СandySize + scene.offsetY,
                    duration: duration,
                    ease: 'Sine.easeOut',
                    onStart: () => {
                    },
                    onComplete: function () {
                        activeTweenCount--;
                        let matches = findMatches(scene);
                        removeMatches(matches, scene);
                    }
                });

            }
        }
    }
}


/**
 * Смещает оставшиеся конфеты вниз после удаления совпадений.
 * @param {Phaser.Scene} scene - Сцена, в которой происходит смещение.
 */
function shiftCandiesDown(scene) {
    const baseDuration = 160; // Базовая продолжительность анимации
    const durationPerCell = 30; // Дополнительная продолжительность анимации на каждую ячейку
    const gridHeight = scene.levelData.tiles.length;
    const gridWidth = scene.levelData.tiles[0].length; // Предполагаем, что все строки имеют одинаковую длину
    let anim = true;

    for (let x = 0; x < gridWidth; x++) {
        for (let y = gridHeight - 1; y >= 0; y--) {
            // Проверяем, должна ли быть конфета в этой позиции согласно сетке уровня
            if (scene.grid[y][x] === null && scene.levelData.tiles[y][x] > 0) {
                let foundCandy = false;

                // Находим первую конфету выше пустой ячейки
                for (let newY = y - 1; newY >= 0; newY--) {
                    if (scene.grid[newY][x] !== null && scene.levelData.tiles[newY][x] > 0) {
                        // Смещаем найденную конфету вниз

                        let fallHeight = 0;
                        for (let emptyY = newY + 1; emptyY <= y; emptyY++) {
                            if (scene.grid[emptyY][x] === null) {
                                fallHeight++;
                            }
                        }

                        let duration = baseDuration + durationPerCell * fallHeight;

                        scene.grid[y][x] = scene.grid[newY][x];
                        scene.grid[newY][x] = null;
                        scene.grid[y][x].y = y; // Обновляем положение конфеты в классе Candy
                        activeTweenCount++;

                        // Анимация смещения вниз
                        scene.tweens.add({
                            targets: scene.grid[y][x].sprite,
                            y: y * scene.СandySize + scene.offsetY,
                            duration: duration,
                            ease: 'Sine.easeOut',
                            onStart: () => {
                                // Анимация смещения вниз
                                if (scene.grid[y][x] !== null && 'ice' in scene.grid[y][x]) {
                                    scene.tweens.add({
                                        targets: scene.grid[y][x].ice,
                                        y: y * scene.СandySize + scene.offsetY,
                                        duration: duration,
                                        ease: 'Sine.easeOut',
                                    });
                                }
                            },
                            onComplete: () => {
                                activeTweenCount--;
                                if (activeTweenCount === 0) {
                                    generateNewCandies(scene);
                                }
                            }
                        });



                        foundCandy = true;
                        break; // Прерываем внутренний цикл, так как конфета найдена
                    }
                }


                if (!foundCandy) {
                    setTimeout(() => {
                        if (activeTweenCount === 0) {
                            generateNewCandies(scene);
                        }
                    }, 300);

                    break; // Если конфета не найдена, прерываем цикл для данного столбца
                }

            }
        }
    }
}

function playDisappearAnimation(sprite, onCompleteCallback) {
    activeTweenCount++;

    sprite.scene.tweens.add({
        targets: sprite,
        alpha: 0,
        scale: 0,
        duration: 260,
        ease: 'Sine.easeIn',
        onComplete: () => {
            sprite.destroy();
            activeTweenCount--;

            if (onCompleteCallback && typeof onCompleteCallback === 'function') {
                onCompleteCallback();
            }
        }
    });
}

/**
 * Создает сетку конфет на игровом поле.
 * @param {Phaser.Scene} scene - Сцена, для которой создается сетка.
 */
function createCandyGrid(scene, levelData) {
    if (loggingEnabled) {
        console.log('Начало создания сетки');
    }
    const grid = scene.grid;

    // Вычисляем gridHeight и gridWidth
    const gridHeight = levelData.tiles.length;
    const gridWidth = levelData.tiles[0].length; // Предполагаем, что все строки имеют одинаковую длину
    const boardBorder = 24; // толщина обводки фигуры
    const tileSize = scene.СandySize;
    const startX = scene.offsetX - tileSize / 2;
    const startY = scene.offsetY - tileSize / 2;


    levelData.tiles.forEach((row, y) => {
        row.forEach((tile, x) => {
            if (tile === 1 || tile === 2) {
                // Проверяем соседние плитки и края
                if (y === 0 || levelData.tiles[y - 1][x] === 0) {
                    if (x === 0) {
                        drawBorderLine(scene, x * tileSize + scene.offsetX / 2 - 2, startY + y * tileSize, startX + (x + 1) * tileSize, startY + y * tileSize + boardBorder, "Верхний", boardBorder);
                    } else if (levelData.tiles[y][x + 1] === 0) {
                        drawBorderLine(scene, startX + x * tileSize, startY + y * tileSize, startX + (x + 1) * tileSize + boardBorder / 10, startY + y * tileSize + boardBorder, "Верхний", boardBorder);
                    } else if (x === levelData.tiles[0].length - 1) {
                        drawBorderLine(scene, startX + x * tileSize, startY + y * tileSize, startX + (x + 1) * tileSize + boardBorder / 10, startY + y * tileSize + boardBorder, "Верхний", boardBorder);
                    } else {
                        drawBorderLine(scene, startX + x * tileSize, startY + y * tileSize, startX + (x + 1) * tileSize, startY + y * tileSize + boardBorder, "Верхний", boardBorder);
                    }
                }

                if (y === levelData.tiles.length - 1 || levelData.tiles[y + 1][x] === 0) {
                    if (x === 0) {
                        drawBorderLine(scene, startX + x * tileSize - boardBorder / 10, startY + (y + 1) * tileSize, startX + (x + 1) * tileSize, startY + (y + 1) * tileSize, "Нижний", boardBorder);
                    } else if (x === levelData.tiles[0].length - 1) {
                        drawBorderLine(scene, startX + x * tileSize, startY + (y + 1) * tileSize, startX + (x + 1) * tileSize + boardBorder / 10, startY + (y + 1) * tileSize, "Нижний", boardBorder);
                    } else {
                        drawBorderLine(scene, startX + x * tileSize, startY + (y + 1) * tileSize, startX + (x + 1) * tileSize, startY + (y + 1) * tileSize, "Нижний", boardBorder);

                    }
                }
                if (x === 0 || levelData.tiles[y][x - 1] === 0) {
                    drawBorderLine(scene,
                        startX + x * tileSize,
                        startY + y * tileSize,
                        startX + x * tileSize + boardBorder,
                        startY + (y + 1) * tileSize,
                        "Левый", boardBorder);
                }
                if (x === row.length - 1 || levelData.tiles[y][x + 1] === 0) {
                    drawBorderLine(scene, startX + (x + 1) * tileSize, startY + y * tileSize, startX + (x + 1) * tileSize, startY + (y + 1) * tileSize, "Правый", boardBorder);
                }
            }
        });
    });

    drawCirclesAtCorners(scene, scene.levelData.tiles, startX, startY, tileSize, boardBorder);

    levelData.tiles.forEach((row, y) => {
        row.forEach((tile, x) => {
            if (tile === 1 || tile === 2) {
                let color = (x + y) % 2 === 0 ? 0x009ADC : 0x66B3DE;
                let size = gridWidth === 7 ? 2 : 7;

                scene.add.rectangle(x * tileSize + scene.offsetX / 2 - size, scene.offsetY - tileSize / 2 + y * tileSize, tileSize, tileSize, color).setOrigin(0, 0);

            }
        });
    });
    for (let y = 0; y < gridHeight; y++) {
        const row = [];
        for (let x = 0; x < gridWidth; x++) {
            let tileValue = levelData.tiles[y][x];
            if (tileValue === 1 || tileValue === 2) {
                let color, isMatch;
                do {
                    color = tileValue === 1 ? getRandomColor(levelData) : getRandomFrozen(levelData);
                    isMatch = checkForInitialMatches(row, grid, x, y, color);
                } while (isMatch);

                let candy;
                if (tileValue === 2) {
                    // Создаем замороженную конфету
                    candy = new FrozenCandy(scene, x, y, color);
                } else {
                    // Создаем обычную конфету
                    candy = new Candy(scene, x, y, color);
                }
                candy.createCandySprite();
                candy.enableSwipe();
                row.push(candy);
                if (loggingEnabled) {
                    console.log(`Конфета создана в позиции (${x}, ${y}), цвет: ${color}, заморожена: ${tileValue === 2}`);
                }



            } else {
                row.push(null);
                if (loggingEnabled) {
                    console.log(`Пустая ячейка в позиции (${x}, ${y})`);
                }
            }
        }
        grid.push(row);
    }
    if (loggingEnabled) {
        console.log('Сетка создана');
    }
}

function drawCirclesAtCorners(scene, tiles, startX, startY, tileSize, boardBorder) {
    let color = 0x2A2B81;
    let graphics = scene.add.graphics();

    for (let y = 0; y < tiles.length; y++) {
        for (let x = 0; x < tiles[y].length; x++) {
            if (tiles[y][x] === 1) {
                // Проверка углов
                let isLeftEdge = x === 0 || tiles[y][x - 1] === 0;
                let isRightEdge = x === tiles[y].length - 1 || tiles[y][x + 1] === 0;
                let isTopEdge = y === 0 || tiles[y - 1][x] === 0;
                let isBottomEdge = y === tiles.length - 1 || tiles[y + 1][x] === 0;

                // Рисование кругов в углах
                if (isLeftEdge && isTopEdge) {
                    graphics.fillCircle(startX + x * tileSize, startY + y * tileSize, boardBorder);
                }
                if (isRightEdge && isTopEdge) {
                    graphics.fillCircle(startX + (x + 1) * tileSize, startY + y * tileSize, boardBorder);
                }
                if (isLeftEdge && isBottomEdge) {
                    graphics.fillCircle(startX + x * tileSize, startY + (y + 1) * tileSize, boardBorder);
                }
                if (isRightEdge && isBottomEdge) {
                    graphics.fillCircle(startX + (x + 1) * tileSize, startY + (y + 1) * tileSize, boardBorder);
                }
            }
        }
    }
}


function drawBorderLine(scene, x1, y1, x2, y2, edgeType, boardBorder) {
    // scene.fillStyle(0x2A2B81, 1); // цвет обводки
    let color = 0x2A2B81;
    // Сдвигаем обводку в зависимости от типа края
    switch (edgeType) {
        case "Верхний":
            y1 -= boardBorder; y2 -= boardBorder;
            break;
        case "Нижний":
            y2 += boardBorder;
            break;
        case "Левый":
            x1 -= boardBorder; x2 -= boardBorder;
            break;
        case "Правый":
            x2 += boardBorder;
            break;
    }
    scene.add.rectangle(x1, y1, x2 - x1, y2 - y1, color).setOrigin(0, 0);

}

function checkForInitialMatches(row, grid, x, y, color) {

    // Проверка на горизонтальные совпадения
    if (x >= 2) {
        const candy1 = row[x - 1];
        const candy2 = row[x - 2];
        if (candy1 && candy2 && candy1.color === color && candy2.color === color) {
            return true;
        }
    }

    // Проверка на вертикальные совпадения
    if (y >= 2) {
        const candy1 = grid[y - 1][x];
        const candy2 = grid[y - 2][x];
        if (candy1 && candy2 && candy1.color === color && candy2.color === color) {
            return true;
        }
    }

    return false;
}

function modifyColorFrequency(colors, level, tiles, targetCandies) {

    // Увеличиваем частоту определенных цветов в зависимости от уровня
    let count = 0;
    let count2 = 0;

    let colorFrequencies = {};

    for (let row of tiles) {
        for (let tile of row) {
            if (tile === 1) {
                count++;
            }
        }
    }
    // уменьшаем частоту енергетиков цветов
    let energy = Math.max(8 - (level - 1) * 0.1, 0);

    let candyNames = Object.keys(targetCandies);
    for (let candyName of candyNames) {
        // уменьшаем частоту собираемых цветов
        colorFrequencies[candyName] = (count - energy) / colors.length + 4;
        count2 = count2 + colorFrequencies[candyName];
    }

    // Структура для хранения частоты каждого цвета
    colors.forEach(color => {
        if (!candyNames.includes(color)) {
            colorFrequencies[color] = (count - count2 - energy) / (colors.length - candyNames.length); // Базовая частота
        }
    });

    colorFrequencies['candy5'] = energy;

  
    // Создаем массив с учетом новых частот
    let modifiedColors = [];
    for (let color in colorFrequencies) {
        for (let i = 0; i < colorFrequencies[color]; i++) {
            modifiedColors.push(color);
        }
    }

    return modifiedColors;
}

function getRandomColor(levelData, currentLevel) {
    // Получаем массив цветов с учетом частоты
    let availableColors = modifyColorFrequency(levelData.colors, levelData.levelNumber, levelData.tiles, levelData.targetCandies);
    return availableColors[Math.floor(Math.random() * availableColors.length)];
}

function getRandomFrozen(levelData) {
    return levelData.frozenCandys[Math.floor(Math.random() * levelData.frozenCandys.length)];
}

class CandyUI extends Phaser.GameObjects.Container {
    constructor(scene, x, y, targetCandies) {
        super(scene, x, y);

       
        this.scene = scene;
        this.targetCandies = targetCandies;
        this.candyUIWidth = 170; // Ширина одного элемента UI
        this.screenWidth = scene.sys.game.config.width; // Ширина экрана/игрового поля
        if (Object.keys(this.targetCandies).length >= 3) {
            length = 3
        } else {
            length = Object.keys(this.targetCandies).length
        }
        this.uiOffsetX = (this.screenWidth - this.candyUIWidth * length) / 2 + this.candyUIWidth / 1.3;

        // Стиль для текста
        const style_targetCandies = {
            fontSize: '40px',
            fontFamily: 'AVENGEANCE',
            fill: '#fff',
            align: 'left'
        };

        let style_title = {
            fontSize: '28px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
        };

        let title = scene.add.text(scene.cameras.main.width / 2, 418, scene.langdata.modal_target, style_title);
        title.setDepth(102);
        title.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        title.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
        this.add(title); // Добавляем иконку в контейнер

        let count = 0; // Инициализируем счетчик

        for (let candyType in this.targetCandies) {
            if (count >= 3) break; // Если уже создано 3 элемента, прекращаем цикл

            // Создание иконки конфеты
            let candyImage = this.scene.add.sprite(this.uiOffsetX - this.candyUIWidth / 2, 510, candyType);
            candyImage.setDepth(102);
            candyImage.setScale(1.1);
            this.add(candyImage); // Добавляем иконку в контейнер

            // Создание текста
            let candyText = this.scene.add.text(candyImage.x + 40, candyImage.y - 30, `X${this.targetCandies[candyType]}`, style_targetCandies);
            candyText.setDepth(102);
            candyText.setShadow(2, 2, 'rgba(0,0,0,0.2)', 2);
            this.add(candyText); // Добавляем текст в контейнер

            // Сохранение ссылок на элементы UI
            this.targetCandies[candyType] = {
                uiImage: candyImage,
                uiText: candyText,
                required: this.targetCandies[candyType],
                collected: 0
            };

            this.uiOffsetX += this.candyUIWidth; // Увеличиваем смещение для следующего элемента
            count++;
        }

        this.scene.add.existing(this); // Добавляем контейнер в сцену
    }

    // Метод для обновления UI
    updateCandyUI(candyType, newCount) {
        if (this.targetCandies[candyType]) {
            this.targetCandies[candyType].uiText.setText(`X${newCount}`);
            this.targetCandies[candyType].collected = newCount;
        }
    }
}

class CoinDisplay extends Phaser.GameObjects.Container {
    constructor(scene,  x, y, textCoinValue, coinWinValue) {
        super(scene, x, y);

        this.scene = scene;
        this.textCoinValue = textCoinValue;
        this.coinWinValue = coinWinValue;

        // Стили для текста
        const style_textCoin = {
            fontSize: '28px',
            fill: '#fff',
            fontFamily: 'Montserrat',
            wordWrap: { width: 230, useAdvancedWrap: true }

        };
        const style_Coin = {
            fontSize: '40px',
            fill: '#fff',
            fontFamily: 'Montserrat',
            wordWrap: { width: 230, useAdvancedWrap: true }

        };


        // Создание и настройка текста
        this.textCoin = scene.add.text(0, 0, this.textCoinValue, style_textCoin);
        this.textCoin.setOrigin(0.5, 0);
        this.textCoin.setShadow(1, 1, 'rgba(0,0,0,0.4)', 0);

        // Создание и настройка значения монет
        this.coinWin = scene.add.text(285, 6, this.coinWinValue, style_Coin);
        this.coinWin.setOrigin(0.5, 0);
        this.coinWin.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);

        // Создание и настройка иконки монеты
        this.bgCoin = scene.add.sprite(270, 0 , 'icon_coin');
        this.bgCoin.setOrigin(0.5, 0);

        // Добавляем элементы в контейнер
        this.add([this.bgCoin, this.textCoin, this.coinWin ]);

        // Добавляем контейнер в сцену
        scene.add.existing(this);
    }

    // Метод для обновления отображаемых значений
    updateCoinDisplay(newTextCoinValue, newCoinWinValue) {
        this.textCoin.setText(newTextCoinValue);
        this.coinWin.setText(newCoinWinValue);
    }
}

/**
 * Находит совпадения конфет на сетке.
 * @return {Array} Массив совпадений.
 */
function findMatches(scene) {
    const grid = scene.grid;
    let matches = [];
    const gridWidth = grid[0].length;
    const gridHeight = grid.length;
    if (loggingEnabled) {
        console.log("Поиск совпадений из четырех конфет");
    }

    clearCandyFlags(grid, scene.levelData); // Очистить флаги использования конфет

    // Поиск совпадений в форме буквы "Т" во всех направлениях
    for (let x = 0; x < gridWidth; x++) {
        for (let y = 0; y < gridHeight; y++) {
            let centerCandy = grid[y][x];
            if (!centerCandy) continue;

            // Проверяем "Т" с горизонтальной осью сверху
            if (y + 2 < gridHeight && x - 1 >= 0 && x + 1 < gridWidth &&
                grid[y + 1][x] && grid[y + 1][x].color === centerCandy.color &&
                grid[y + 2][x] && grid[y + 2][x].color === centerCandy.color &&
                grid[y][x - 1] && grid[y][x - 1].color === centerCandy.color &&
                grid[y][x + 1] && grid[y][x + 1].color === centerCandy.color) {
                matches.push({ x, y, type: 'bomb' });
            }

            // Проверяем "Т" с горизонтальной осью снизу
            if (y - 2 >= 0 && x - 1 >= 0 && x + 1 < gridWidth &&
                grid[y - 1][x] && grid[y - 1][x].color === centerCandy.color &&
                grid[y - 2][x] && grid[y - 2][x].color === centerCandy.color &&
                grid[y][x - 1] && grid[y][x - 1].color === centerCandy.color &&
                grid[y][x + 1] && grid[y][x + 1].color === centerCandy.color) {
                matches.push({ x, y, type: 'bomb' });
            }

            // Проверяем "Т" с вертикальной осью справа
            if (x + 2 < gridWidth && y - 1 >= 0 && y + 1 < gridHeight &&
                grid[y][x + 1] && grid[y][x + 1].color === centerCandy.color &&
                grid[y][x + 2] && grid[y][x + 2].color === centerCandy.color &&
                grid[y - 1][x] && grid[y - 1][x].color === centerCandy.color &&
                grid[y + 1][x] && grid[y + 1][x].color === centerCandy.color) {
                matches.push({ x, y, type: 'bomb' });
            }

            // Проверяем "Т" с вертикальной осью слева
            if (x - 2 >= 0 && y - 1 >= 0 && y + 1 < gridHeight &&
                grid[y][x - 1] && grid[y][x - 1].color === centerCandy.color &&
                grid[y][x - 2] && grid[y][x - 2].color === centerCandy.color &&
                grid[y - 1][x] && grid[y - 1][x].color === centerCandy.color &&
                grid[y + 1][x] && grid[y + 1][x].color === centerCandy.color) {
                matches.push({ x, y, type: 'bomb' });
            }
        }
    }


    // Поиск горизонтальных совпадений из пяти конфет
    for (let y = 0; y < gridHeight; y++) {
        for (let x = 0; x < gridWidth - 4; x++) {
            if (checkAndMarkLineMatch(grid, x, y, 5, 'horizontal')) {
                matches.push({ x, y, type: 'lightning', color: grid[y][x].color});
            }
        }
    }

    // Поиск вертикальных совпадений из пяти конфет
    for (let x = 0; x < gridWidth; x++) {
        for (let y = 0; y < gridHeight - 4; y++) {
            if (checkAndMarkLineMatch(grid, x, y, 5, 'vertical')) {
                matches.push({ x, y, type: 'lightning', color: grid[y][x].color });
            }
        }
    }

    // Поиск горизонтальных совпадений из ровно четырех конфет
    for (let y = 0; y < gridHeight; y++) {
        for (let x = 0; x <= gridWidth - 4; x++) {
            if (checkAndMarkLineMatch(grid, x, y, 4, 'horizontal') &&
                (x + 4 >= gridWidth || grid[y][x + 4] === null || grid[y][x + 4].color !== grid[y][x].color)) {
                matches.push({ x, y, type: 'rocket', direction: 'horizontal'});
            }
        }
    }

    // Поиск вертикальных совпадений из ровно четырех конфет
    for (let x = 0; x < gridWidth; x++) {
        for (let y = 0; y <= gridHeight - 4; y++) {
            if (checkAndMarkLineMatch(grid, x, y, 4, 'vertical') &&
                (y + 4 >= gridHeight || grid[y + 4][x] === null || grid[y + 4][x].color !== grid[y][x].color)) {
                matches.push({ x, y, type: 'rocket', direction: 'vertical' });
            }
        }
    }

    // Поиск горизонтальных совпадений
    for (let y = 0; y < gridHeight; y++) {
        for (let x = 0; x < gridWidth - 2; x++) {
            let candy1 = grid[y][x];
            let candy2 = grid[y][x + 1];
            let candy3 = grid[y][x + 2];
            if (candy1 && candy2 && candy3 && candy1.color === candy2.color && candy1.color === candy3.color) {
                matches.push({ x, y });
                matches.push({ x: x + 1, y });
                matches.push({ x: x + 2, y });
            }
        }
    }

    // Поиск вертикальных совпадений
    for (let x = 0; x < gridWidth; x++) {
        for (let y = 0; y < gridHeight - 2; y++) {
            let candy1 = grid[y][x];
            let candy2 = grid[y + 1][x];
            let candy3 = grid[y + 2][x];
            if (candy1 && candy2 && candy3 && candy1.color === candy2.color && candy1.color === candy3.color) {
                matches.push({ x, y });
                matches.push({ x, y: y + 1 });
                matches.push({ x, y: y + 2 });
            }
        }
    }
    if (matches.length > 0) {
        if (loggingEnabled) {
            console.log("Найдены совпадения для бомбы:", matches);
        }
    }

    // Добавьте логику для проверки и разморозки замороженных конфет
    matches.forEach(match => {
        checkAndUnfreezeCandiesAround(match.x, match.y, scene.levelData, grid);
    });

    return matches;
}



function checkAndMarkLineMatch(grid, x, y, length, direction) {
    let matchFound = true;

    // Проверяем, есть ли совпадение
    for (let i = 0; i < length; i++) {
        let checkX = direction === 'horizontal' ? x + i : x;
        let checkY = direction === 'vertical' ? y + i : y;
        if (!grid[checkY] || !grid[checkY][checkX] || grid[checkY][checkX] === null || grid[checkY][checkX].usedInMatch) {
            matchFound = false;
            break;
        }
    }

    // Если совпадение есть, проверяем, что все конфеты одного цвета
    if (matchFound) {
        const color = grid[y][x].color;
        for (let i = 0; i < length; i++) {
            let checkX = direction === 'horizontal' ? x + i : x;
            let checkY = direction === 'vertical' ? y + i : y;
            if (grid[checkY][checkX].color !== color) {
                matchFound = false;
                break;
            }
        }
    }

    // Отмечаем использованные конфеты
    if (matchFound) {
        for (let i = 0; i < length; i++) {
            let checkX = direction === 'horizontal' ? x + i : x;
            let checkY = direction === 'vertical' ? y + i : y;
            grid[checkY][checkX].usedInMatch = true;
        }
    }

    return matchFound;
}


function clearCandyFlags(grid, levelData) {
    const gridHeight = levelData.tiles.length;
    const gridWidth = levelData.tiles[0].length; // Предполагаем, что все строки имеют одинаковую длину

    for (let y = 0; y < gridHeight; y++) {
        for (let x = 0; x < gridWidth; x++) {
            if (grid[y][x]) {
                grid[y][x].usedInMatch = false;
            }
        }
    }
}

class FrozenCandy extends Candy {
    constructor(scene, x, y, color) {
        super(scene, x, y, color);
        this.isFrozen = true;
    }

    createCandySprite() {
        super.createCandySprite();

        const color = getRandomColor(this.scene.levelData);

        this.sprite.setTexture(color);
        if (loggingEnabled) {
            console.log("Создан спрайт заморозки: " + color);
        }
    }

    handleSwipe() {
        if (this.isFrozen) {
            if (loggingEnabled) {
                console.log("Замороженная конфета");
            }
            // Дополнительная логика для замороженной конфеты
            return; // Прекращаем обработку свайпа
        }

        // Вызываем handleSwipe родительского класса только если конфета не заморожена
        super.handleSwipe();
    }
}

function checkAndUnfreezeCandiesAround(x, y, levelData, grid) {
    // Проверка только непосредственно соседних ячеек
    const offsets = [[-1, 0], [1, 0], [0, -1], [0, 1]]; // Влево, вправо, вверх, вниз
    offsets.forEach(offset => {
        let checkX = x + offset[0];
        let checkY = y + offset[1];
        if (checkX >= 0 && checkX < levelData.gridWidth && checkY >= 0 && checkY < levelData.gridHeight) {
            let candy = grid[checkY][checkX];
            if (candy && candy.isFrozen) {
                candy.isFrozen = false;
                // Обновите визуальное отображение размороженной конфеты
                const frozenIndex = levelData.frozenCandys.indexOf(candy.sprite.texture.key);
                if (frozenIndex !== -1) {
                    console.log(candy.ice);
                    candy.sprite.setTexture('candy' + (frozenIndex + 1));
                    candy.color = 'candy' + (frozenIndex + 1); // Обновляем цвет конфеты
                }
            }
        }
    });
}

function checkAndUnfreezeCandy(x, y, levelData, grid) {
    let candy = grid[y][x];
    if (candy && candy instanceof FrozenCandy && candy.isFrozen) {
        candy.isFrozen = false;
        // Обновите визуальное отображение размороженной конфеты
        const frozenIndex = levelData.frozenCandys.indexOf(candy.sprite.texture.key);
        if (frozenIndex !== -1) {
            candy.sprite.setTexture('candy' + (frozenIndex + 1));
            candy.color = 'candy' + (frozenIndex + 1); // Обновляем цвет конфеты
            candy.ice.destroy();
        }
    }
}

function headerGame(scene) {
    let h1 = {
        fontSize: '64px',
        fill: '#fff',
        fontFamily: 'AVENGEANCE',
        padding: { right: 4 }
    };

    let h2 = {
        fontSize: '32px',
        fill: '#fff',
        fontFamily: 'AVENGEANCE',
        padding: { right: 4 }
    };

    scene.add.sprite(scene.cameras.main.width / 2, 100, 'header').setScale(1);
    scene.scoreText = scene.add.text(40, 30, '0', h1);
    scene.scoreText_ = scene.add.text(40, 95, scene.langdata.score_text, h2);
    scene.movesText = scene.add.text(600, 30, scene.levelData.movesLeft, h1);
    scene.scoreText_ = scene.add.text(580, 95, scene.langdata.moves_text, h2);

    if (scene.levelData.tiles[0].length === 6) {
        var boardScale = .95;
        var top = 620;
    } else if (scene.levelData.tiles[0].length === 7) {
        var boardScale = .8;
        var top = 670;
    } else {
        var boardScale = 1;
        var top = 600;
    }

    // scene.add.sprite(scene.cameras.main.width / 2, top, 'gameBoard' + scene.levelNumber).setScale(boardScale);

    collectedCandyUI(scene);
    onTimer(scene);
}


  
function collectedCandyUI(scene) {
   
    var savedState = loadGameState();

    // Загрузка целей уровня
    if (savedState.targetCandies) {
        for (let candyType in savedState.targetCandies) {
            targetCandies[candyType] = {
                required: savedState.targetCandies[candyType],
                collected: 0
            };
        }
    } else {
        var gameState = {
            targetCandies: scene.levelData.targetCandies,
        };
        saveGameState(gameState);

        for (let candyType in scene.levelData.targetCandies) {
            targetCandies[candyType] = {
                required: scene.levelData.targetCandies[candyType],
                collected: 0
            };
        }
    }

    const candyUIWidth = 80; // Ширина одного элемента UI
    const totalWidth = candyUIWidth * Object.keys(targetCandies).length; // Общая ширина всех элементов UI
    const screenWidth = scene.sys.game.config.width; // Ширина экрана/игрового поля
    let uiOffsetX = (screenWidth - totalWidth) / 2 + candyUIWidth / 2; // Начальное смещение по X для центрирования

    for (let candyType in targetCandies) {
        let candyImage = scene.add.sprite(uiOffsetX, 64, candyType).setScale(1);

        // Создаем текст и устанавливаем его начальное положение в ту же точку, что и изображение
        let candyText = scene.add.text(uiOffsetX, candyImage.y + 50, `${targetCandies[candyType].required}`, { fontSize: '32px', fontFamily: 'AVENGEANCE', fill: '#fff', align: 'center' });

        // Устанавливаем точку привязки текста в центр (0.5, 0.5)
        candyText.setOrigin(0.5, 0);

        // Сохраняем ссылки на текстовые элементы для обновления
        targetCandies[candyType].uiText = candyText;

        uiOffsetX += candyUIWidth; // Увеличиваем смещение для следующего элемента
    }
}


function updateCollectedCandyUI(scene) {
    for (let candyType in targetCandies) {
        let target = targetCandies[candyType];
        let remaining = target.required - target.collected;

        var savedState = loadGameState();
        savedState.targetCandies[candyType] = remaining;

        var gameState = {
            targetCandies: savedState.targetCandies,
        };
        saveGameState(gameState);

        // Если оставшееся количество равно 0, обновляем UI и продолжаем
        if (remaining <= 0) {
            target.uiText.setText('0');
            continue; // Пропускаем остальную часть цикла
        }

        // Создаем объект для анимации
        let animatedValue = { value: target.uiText.animatedValue || target.required };

        // Анимация изменения значения
        scene.tweens.add({
            targets: animatedValue,
            value: remaining,
            duration: 500, // продолжительность анимации в миллисекундах
            ease: 'Sine.easeInOut', // тип анимации
            onUpdate: function (tween) {
                const val = Math.round(tween.getValue());
                target.uiText.setText(`${val}`);
            },
            onComplete: function () {
                target.uiText.animatedValue = remaining; // обновляем значение после анимации
            }
        });
    }
}

class LevelScene extends Phaser.Scene {
    constructor(levelNumber, data) {
        super({});
        this.levelNumber = levelNumber;
        this.grid = [];
        this.score = 0;
        this.explosionSpriteSize = .3;
        this.offsetY = 350;
        this.levelData;
        this.modal;
        this.loadingProgress = 0; // Добавлено для отслеживания прогресса загрузки
        this.userId = data.userId;
        this.userCoins = data.userCoins;
        this.userEnergy = data.userEnergy;
        this.levelNumber = data.levelNumber;
        this.coinWin = data.coinWin;
        this.lang = localStorage.getItem('locale');

        this.instantGift = data.instantGift;
        this.langdata;
        this.totalSteps = 0;
    }


    goToNextLevel() {
        removePropertyFromGameState('finish');
        removePropertyFromGameState('score');
        removePropertyFromGameState('movesLeft');
        removePropertyFromGameState('targetCandies');
        removePropertyFromGameState('level');
        this.scene.stop();
        // startOrRestartGame();
        window.location.reload();
    }

    preload() {
        if (this.cache.json.exists('levelData')) {
            this.cache.json.remove('levelData');
        }

        this.load.json('lang', `lang/${this.lang}.json`);
        this.load.once('filecomplete-json-lang', function () {
            this.langdata = this.cache.json.get('lang');
            this.load.start();
        }, this);

        this.load.json('levelData', `levels/${this.levelNumber}.json`);
        this.load.once('filecomplete-json-levelData', function () {
            this.levelData = this.cache.json.get('levelData');
            this.load.start();
        }, this);


        for (let i = 1; i <= 6; i++) {
            this.load.image('candy' + i, 'img/candies/candy' + i + '.png');
        }

        for (let i = 1; i <= 9; i++) {
            this.load.image('explosion' + i, 'img/explosion_bomb/explosion' + i + '.png');
        }

        for (let i = 1; i <= 13; i++) {
            this.load.image('explosions' + i, 'img/explosion_candy/explosions' + i + '.png');
        }

        this.load.image('bomb', 'img/bomb/bomb_normal.png');

        this.load.image('rocket', 'img/rocket/rocket_normal.png');
        this.load.image('lightning', 'img/lightning/lightning_normal.png');
        this.load.image('header', 'img/ui/header.png');
        this.load.image('popup', 'img/ui/popup.png');
        this.load.image('modal_end', 'img/ui/modal_end.png');
        this.load.image('modal_err', 'img/ui/modal_err.png');
        this.load.image('energy', 'img/ui/energy.png');
        this.load.image('modal_gift_box', 'img/ui/modal_gift_box.png');
        this.load.image('modal_gift_mobi', 'img/ui/modal_gift_mobi.png');
        this.load.image('modal_hint_energy', 'img/ui/modal_hint_energy.png');
        this.load.image('modal_hint_rocket', 'img/ui/modal_hint_rocket.png');
        this.load.image('modal_hint_light', 'img/ui/modal_hint_light.png');
        this.load.image('modal_hint_bomb', 'img/ui/modal_hint_bomb.png');
        this.load.image('modal_err_energy', 'img/ui/modal_err_energy.png');


        this.load.image('btnClose', 'img/ui/btnClose.png');
        this.load.image('btn', 'img/ui/btn_normal.png');
        this.load.image('coins_bg', 'img/ui/coins_bg.png');
        this.load.image('icon_coin', 'img/ui/icon_coin.png');
        this.load.image('energy_bg', 'img/ui/energy_bg.png');
        this.load.image('ice', 'img/frozen/ice.png');


        // Создаем текст для отображения процентов загрузки
        let loadingText = this.add.text(config.scale.width / 2, config.scale.height / 2, '0%', { fontSize: '64px', fill: '#FFFFFF', fontFamily: 'AVENGEANCE', }).setOrigin(0.5);
        this.loadingText = loadingText; // Сохраняем ссылку на текст загрузки

        // Обновляем текст процентов загрузки во время загрузки
        this.load.on('progress', function (value) {
            this.updateLoadingProgress(0.5, value);
        }, this);

        this.load.on('complete', function () {
            this.updateLoadingProgress(0.99, 1);
            this.createLevel(); // Перенесен сюда
        }, this);

        this.load.start();

    }

    createLevel() {
        var savedState = loadGameState();
        if (savedState.score) {
            this.score = savedState.score;
            this.levelData.movesLeft = savedState.movesLeft;

            var savedStateCandiesKeys = Object.keys(savedState.targetCandies);
            var levelDataCandiesKeys = Object.keys(this.levelData.targetCandies);
        
            var allCandiesMatch = savedStateCandiesKeys.length === levelDataCandiesKeys.length && 
            savedStateCandiesKeys.every(function(candy) {
                return levelDataCandiesKeys.includes(candy);
            });

            if (!allCandiesMatch) {
                removePropertyFromGameState('finish');
                removePropertyFromGameState('score');
                removePropertyFromGameState('movesLeft');
                removePropertyFromGameState('targetCandies');
                removePropertyFromGameState('level');
                this.scene.stop();
                startOrRestartGame();
            }
        }



        this.СandySize = (config.scale.width - 80) / this.levelData.tiles[0].length; // Размер конфеты
        this.СandySpriteSize = this.СandySize / 90; // Размер конфеты
        this.offsetX = this.СandySize / 2 + 40; // Пример смещения по X        




        updateScoreUI(this);
        headerGame(this);
        animations(this);
        createCandyGrid(this, this.levelData);

        this.startModal = new Modal(
            this, //сцена
            this.langdata.level + this.levelNumber, //Заголовок
            true, //Показать кол-во коинов за прохождение true / fasle
            true, //Показать кол-во энергии true / fasle
            true, //Показать кол-во коинов true / fasle
            true, //Показать задание true / fasle
            true, //Показать btn true / fasle
            this.langdata.btn_start,
            'start',
            false, //Показать очки btn true / fasle


        );
        this.startModal.setDepth(101);

        this.endModal = new Modal(
            this, //сцена
            this.langdata.end_title, //Заголовок
            false, //Показать кол-во коинов за прохождение true / fasle
            true, //Показать кол-во энергии true / fasle
            true, //Показать кол-во коинов true / fasle
            false, //Показать задание true / fasle
            true, //Показать btn true / fasle
            this.langdata.btn_end,
            'end',
            true, //Показать очки btn true / fasle
        );
        this.endModal.setDepth(101);
        // this.startModal.hideModal(); // Скрываем модальное окно по умолчанию

 
        this.giftModal = new Modal(
            this, //сцена
            this.langdata.gift_title, //Заголовок
            false, //Показать кол-во коинов за прохождение true / fasle
            false, //Показать кол-во энергии true / fasle
            false, //Показать кол-во коинов true / fasle
            false, //Показать задание true / fasle
            true, //Показать btn true / fasle
            this.langdata.btn_end,
            'gift',
            false, //Показать очки btn true / fasle

        );
        this.giftModal.setDepth(101);


        this.errorEnergyModal = new ModalEnergy(
            this, //сцена
            'modal_err_energy',
            this.langdata.err_energy_title, //Заголовок
            this.langdata.err_energy_text, //Заголовок
            this.langdata.err_energy_text,
            this.langdata.btn_check,
        );
        this.errorEnergyModal.setDepth(101);

        if (this.userEnergy != 0) {
            if (savedState.finish === true) {
                this.endModal.showModal();
            } else {
                this.startModal.showModal();
    
            }
        } else {
            this.errorEnergyModal.showModal();

        }

    }

    updateLoadingProgress(part, value) {
        this.loadingProgress = part * value;
        this.loadingText.setText(parseInt(this.loadingProgress * 100) + '%');
    }



    create() {


    }
}

class LightningCandy extends Candy {
    constructor(scene, x, y) {
        super(scene, x, y, 'lightning');
        this.hasLightning = true;
        this.createCandySprite();
    }

    createCandySprite() {
        super.createCandySprite();
        this.sprite.setTexture('lightning');
        this.sprite.setScale(0.1);

        // Анимация появления
        this.scene.tweens.add({
            targets: this.sprite,
            scaleX: this.scene.СandySpriteSize, // конечный масштаб по X
            scaleY: this.scene.СandySpriteSize, // конечный масштаб по Y
            ease: 'Back.easeOut', // тип анимации, можно выбрать другой
            duration: 500, // продолжительность анимации в миллисекундах
            delay: 300,
        });
    }

    explodeCandy(color) {
        const delayBetweenExplosions = 30;
        this.scene.score += 200;

        // Проходим по всему grid, ищем конфеты совпадающие по цвету и уничтожаем их
        for (let y = 0; y < this.gridHeight; y++) {
            for (let x = 0; x < this.gridWidth; x++) {
                if (this.grid[y][x] && this.grid[y][x].color === color) {
                    this.scene.time.delayedCall(delayBetweenExplosions * x, () => {

                        // Создание спрайта анимации взрыва в позиции бомбы
                        let explosion = this.scene.add.sprite(x * this.scene.СandySize + this.scene.offsetX, y * this.scene.СandySize + this.scene.offsetY, 'explosion2').setScale(this.scene.explosionSpriteSize);
                        explosion.setDepth(10);
                        explosion.play('explode2');

                        // Уничтожение спрайта анимации после завершения
                        explosion.on('animationcomplete', () => {
                            explosion.destroy(); // or explosion.setVisible(false);
                        });
                    });
                    playDisappearAnimation(this.grid[y][x].sprite, () => {
                        this.scene.score += 10;
                        // Проверяем, не превышено ли количество собранных конфет
                        if (targetCandies[color] && targetCandies[color].collected < targetCandies[color].required) {
                            targetCandies[color].collected++;
                        }

                        if (color === 'candy5') {
                            updateMovesUI(this.scene, 'plus'); // Увеличиваем количество шагов
                        }

                    });

                  
                    this.grid[y][x] = null;
                }
            }
        }
        playDisappearAnimation(this.grid[this.y][this.x].sprite, () => { });
        this.grid[this.y][this.x] = null;

        updateCollectedCandyUI(this.scene);
        checkLevelCompletion(this.scene);
        
        // Создание анимации молнии в текущей позиции конфеты
        let lightningEffect = this.scene.add.sprite(this.sprite.x, this.sprite.y, 'explosion1').setScale(this.scene.explosionSpriteSize);
        lightningEffect.setDepth(10);

        lightningEffect.play('explode');

        lightningEffect.on('animationcomplete', () => {
            lightningEffect.destroy();
            shiftCandiesDown(this.scene);
        });
    }
}

// Функция для создания молнии
function createLightningCandy(x, y, scene, color) {
    const lightningCandy = new LightningCandy(scene, x, y, color);
    lightningCandy.enableSwipe();
    scene.grid[y][x] = lightningCandy;

    if (loggingEnabled) {
        console.log(`Молния создана в позиции (${x}, ${y}) с цветом ${color}`);
    }
}

// Константы и переменные игры
let loggingEnabled = false;
let activeTweenCount = 0;
let targetCandies = {};
var game;
 
// Конфигурация Phaser игры
var config = {
    type: Phaser.AUTO,
    transparent: true, // Делаем фон холста прозрачным
    antialias: true,
    scale: {
        mode: Phaser.Scale.FIT,
        parent: 'game',
        width: 720, // Установите начальную ширину
        height: 1180, // Установите начальную высоту
        autoCenter: Phaser.Scale.CENTER_HORIZONTALLY,
    },
    scene: []
};
headers: {
    
}

function startOrRestartGame() {
    fetch('/api/' + localStorage.getItem('locale') + '/games/start', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer '+ localStorage.getItem('token'),
            'Accept': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (game) {
                game.destroy(true);
            }
            data = data.data;
            var font = new FontFaceObserver('AVENGEANCE');
            font.load().then(function () {
                game = new Phaser.Game(config);

                var savedState = loadGameState();
                if (savedState) {
                    if (savedState.level != data.levelNumber) {
                        removePropertyFromGameState('finish');
                        removePropertyFromGameState('score');
                        removePropertyFromGameState('movesLeft');
                        removePropertyFromGameState('targetCandies');
                        saveGameState({ level: data.levelNumber });
                    }
                    if (savedState.movesLeft <= 0) {
                        removePropertyFromGameState('finish');
                        removePropertyFromGameState('score');
                        removePropertyFromGameState('movesLeft');
                        removePropertyFromGameState('targetCandies');
                        saveGameState({ level: data.levelNumber });
                    }
                } else {
                    saveGameState({ level: data.levelNumber });
                }


                var levelToStart = `Level${data.levelNumber}`;

                game.scene.add(levelToStart, new LevelScene(savedState.level ? savedState.level : data.levelNumber, data));
                game.scene.start(levelToStart);

            }).catch(function () {
                console.log('AVENGEANCE failed to load.');
            });
        })
        .catch(error => {
            console.error('Ошибка при получении данных:', error);
        });
}

function endGame(data) {
    fetch('/api/' + localStorage.getItem('locale') + '/games/finish', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json',
        },
        body: JSON.stringify(data) // данные для отправки
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error('Ошибка при отправке данных:', error);
        });
}


function lotteryGame(data) {
    fetch('/api/' + localStorage.getItem('locale') + '/games/prize', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
            'Accept': 'application/json',
        },
        body: JSON.stringify(data) // данные для отправки
    })
        .then(response => response.json())
        .then(data => {
            // console.log(data);
        })
        .catch(error => {
            console.error('Ошибка при отправке данных:', error);
        });
}

startOrRestartGame();


class Modal extends Phaser.GameObjects.Container {
    constructor(scene, title_, showCoinWin, showUserEnergy, showUserCoin, showCandyUI, showButton, textButton, callbackButton, showScoreUI) {
        super(scene);

        this.animatedElements = [];

        let style_textLevel = {
            fontSize: '64px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
        };


        const style_Coin = {
            fontSize: '40px',
            fill: '#fff',
            fontFamily: 'Montserrat',
            wordWrap: { width: 230, useAdvancedWrap: true }

        };

        let style_title = {
            fontSize: '28px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
            wordWrap: { width: 430, useAdvancedWrap: true },
        };

        let style_titlegift = {
            fontSize: '28px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
            wordWrap: { width: 430, useAdvancedWrap: true },
            align: 'center'
        };

        let style_titlegift2 = {
            fontSize: '32px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
            wordWrap: { width: 430, useAdvancedWrap: true },
            align: 'center'
        };
        // Фон модального окна
        const background = scene.add.graphics({ fillStyle: { color: '0x0F0F59', alpha: .8 } });
        background.fillRect(0, 0, scene.cameras.main.width, scene.cameras.main.height);
        background.setDepth(100);
        this.add(background);
        background.elementId = 'background'; // Добавляем пользовательское свойство

        if (showUserCoin === true) {
            const userCoinBG = scene.add.sprite(scene.cameras.main.width / 1.21, 20, 'coins_bg')
            userCoinBG.setDepth(102);
            userCoinBG.setOrigin(0.5, 0);
            this.add(userCoinBG);

            const userCoin = scene.add.text((scene.cameras.main.width / 1.17), userCoinBG.y + 8, scene.userCoins, style_Coin);
            userCoin.setDepth(103);
            userCoin.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
            userCoin.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
            this.add(userCoin);

            this.animatedElements.push(userCoin, userCoinBG);
        }

        if (showUserEnergy === true) {
            const userEnergyBG = scene.add.sprite(100, 20, 'energy_bg')
            userEnergyBG.setDepth(102);
            userEnergyBG.setOrigin(0.5, 0);
            this.add(userEnergyBG);

            const userEnergy = scene.add.text(120, userEnergyBG.y + 7, scene.userEnergy, style_Coin);
            userEnergy.setDepth(103);
            userEnergy.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
            userEnergy.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
            this.add(userEnergy);

            this.animatedElements.push(userEnergy, userEnergyBG);

        }
        if (callbackButton === 'start') {
            var container = scene.add.sprite(scene.cameras.main.width / 2, 200, 'popup');
            
        } else if (callbackButton === 'end') {


            var container = scene.add.sprite(scene.cameras.main.width / 2, 105, 'modal_end');
        } else if (callbackButton === 'err') {
            var container = scene.add.sprite(scene.cameras.main.width / 2, 200, 'modal_err');
        } else if (callbackButton === 'gift') {
            if (scene.instantGift === 'box') {
                var container = scene.add.sprite(scene.cameras.main.width / 2, 200, 'modal_gift_box');
            } else {
                var container = scene.add.sprite(scene.cameras.main.width / 2, 200, 'modal_gift_mobi');
            }
        }

        container.elementId = 'container'; // Добавляем пользовательское свойство
        container.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        container.setScale(.9);
        container.setDepth(101);
        this.add(container);

        if (showCandyUI === true) {
            const candyUI = new CandyUI(scene, 0, 0, scene.levelData.targetCandies);
            candyUI.setDepth(102);
            this.add(candyUI);

            this.animatedElements.push(candyUI);

        }

        if (showScoreUI === true) {
            const scoreUI = new Score(scene, 0, 50);
            scoreUI.setDepth(102);
            this.add(scoreUI);

            this.animatedElements.push(scoreUI);

        }

        if (showCoinWin === true) {
            const coinDisplay = new CoinDisplay(scene, 240, callbackButton === 'end' ? 660 : 640, callbackButton === 'start' ? scene.langdata.modal_coins2 : scene.langdata.modal_coins, scene.coinWin);
            coinDisplay.setDepth(102);
            this.add(coinDisplay);

            this.animatedElements.push(coinDisplay);

        }

        if (callbackButton === 'start') {
            const btnClose = scene.add.sprite(scene.cameras.main.width / 1.21, 160, 'btnClose')
            btnClose.setDepth(103);
            btnClose.setOrigin(0, 0);
            btnClose.setInteractive(); // делаем кнопку интерактивной
            btnClose.on('pointerdown', function () {
                window.location.href = '/' + localStorage.getItem('locale') + '/profile';
            });
            this.add(btnClose);
            this.animatedElements.push(btnClose);
    
        } else if (callbackButton === 'end') {
            const btnClose = scene.add.sprite(scene.cameras.main.width / 1.21, 220, 'btnClose')
            btnClose.setDepth(103);
            btnClose.setOrigin(0, 0);
            btnClose.setInteractive(); // делаем кнопку интерактивной
            btnClose.on('pointerdown', function () {
                window.location.href = '/' + localStorage.getItem('locale') + '/profile';
            });
            this.add(btnClose);
            this.animatedElements.push(btnClose);
    
        }
     
        let title = scene.add.text((scene.cameras.main.width / 2), callbackButton === 'end' ? 300 : 240, title_, style_textLevel);
        title.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        title.setShadow(2, 2, 'rgba(0,0,0,0.4)', 2);
        title.setDepth(102);
        this.add(title);

        if (callbackButton === 'err' || callbackButton === 'gift') {
            let text = scene.add.text(scene.cameras.main.width / 2, callbackButton === 'gift' ? 650 : 418, scene.langdata.gift_text, callbackButton === 'gift' ? style_titlegift : style_title);
            text.setDepth(102);
            text.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
            text.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
            this.add(text); // Добавляем иконку в контейнер
            this.animatedElements.push(text);

        }

        if (callbackButton === 'gift') {
            if (scene.instantGift === 'box') {
                var gift = scene.add.text(scene.cameras.main.width / 2, 740, scene.langdata.gift_box, style_titlegift2);
            } else {
                var gift = scene.add.text(scene.cameras.main.width / 2, 740, scene.langdata.gift_mobi, style_titlegift2);

            }
            gift.setDepth(102);
            gift.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
            gift.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
            this.add(gift); // Добавляем иконку в контейнер
            this.animatedElements.push(gift);

        }

        
        if (showButton === true) {
            var button = new Button(scene, scene.cameras.main.width / 2, callbackButton === 'end' ? 810 : 820 && callbackButton == 'start' ? 820 : 820 && callbackButton == 'err' ? 620 : 820 || callbackButton == 'gift' ? 900 : 820, textButton, () => {
                if (callbackButton === 'start') {
                    this.hideModal();

                    if (scene.levelNumber === 1) {
                        setTimeout(() => {
                            scene.hintModal = new ModalHint(
                                scene, //сцена
                                'modal_hint_energy',
                                scene.langdata.hint_title, //Заголовок
                                scene.langdata.hint_subtitle_energy, //Заголовок
                                scene.langdata.hint_text_energy,
                                scene.langdata.btn_end,
                            );
                            scene.hintModal.setDepth(101);
                            scene.hintModal.showModal();
                        }, 320);
                    }

                    if (scene.levelNumber === 2) {
                        setTimeout(() => {
                            scene.hintModal = new ModalHint(
                                scene, //сцена
                                'modal_hint_rocket',
                                scene.langdata.hint_title, //Заголовок
                                scene.langdata.hint_subtitle_rocket, //Заголовок
                                scene.langdata.hint_text_rocket,
                                scene.langdata.btn_end,
                            );
                            scene.hintModal.setDepth(101);
                            scene.hintModal.showModal();
                        }, 320);
                    }

                    if (scene.levelNumber === 3) {
                        setTimeout(() => {
                            scene.hintModal = new ModalHint(
                                scene, //сцена
                                'modal_hint_bomb',
                                scene.langdata.hint_title, //Заголовок
                                scene.langdata.hint_subtitle_bomb, //Заголовок
                                scene.langdata.hint_text_bomb,
                                scene.langdata.btn_end,
                            );
                            scene.hintModal.setDepth(101);
                            scene.hintModal.showModal();
                        }, 320);
                    }

                    if (scene.levelNumber === 4) {
                        setTimeout(() => {
                            scene.hintModal = new ModalHint(
                                scene, //сцена
                                'modal_hint_light',
                                scene.langdata.hint_title, //Заголовок
                                scene.langdata.hint_subtitle_light, //Заголовок
                                scene.langdata.hint_text_light,
                                scene.langdata.btn_end,
                            );
                            scene.hintModal.setDepth(101);
                            scene.hintModal.showModal();
                        }, 320);
                    }

                } else if (callbackButton === 'end') {
                    this.hideModal()
                    scene.goToNextLevel();
                } else if (callbackButton === 'err') {
                    // window.location.href = 'https://orbit-promo.kz/ru/profile';
                } else {
                    this.hideModal()
                }
            }, 'normal');
            button.elementId = 'button'; // Добавляем пользовательское свойство
            button.setScale(.9);
            button.setDepth(102);
            this.add(button);
            this.animatedElements.push(button);
        }

        if (callbackButton === 'err') {
            const button2 = new ButtonBorder(scene, scene.cameras.main.width / 2, button.y + 120, scene.langdata.btn_friend, () => {
                // window.location.href = 'https://orbit-promo.kz/ru/profile';
            }, 'border');
            button2.elementId = 'button'; // Добавляем пользовательское свойство
            button2.setScale(.9);
            button2.setDepth(102);
            this.add(button2);
            this.animatedElements.push(button2);
        }

        this.animatedElements.push(container, title, background);

        // Делаем модальное окно интерактивным
        this.setInteractive(new Phaser.Geom.Rectangle(0, 0, scene.cameras.main.width, scene.cameras.main.height), Phaser.Geom.Rectangle.Contains);
        // Добавляем модальное окно в сцену
        scene.add.existing(this);
        this.setVisible(false);
        this.setActive(false);
    }

    // Метод для показа модального окна
    showModal() {

        this.animatedElements.forEach((element) => {
            var setting;
            if (element.elementId === 'container' || element.elementId === 'button') {
                setting = {
                    targets: element,
                    y: { from: -100, to: element.y },
                    scale: { from: 0, to: .9 },
                    alpha: { from: 0, to: 1 },
                    duration: 360,
                    ease: 'Back.easeOut'
                }

            } else if (element.elementId === 'background') {
                setting = {
                    targets: element,
                    alpha: { from: 0, to: 1 },
                    duration: 360,
                    ease: 'Back.easeOut'
                }

            } else {
                setting = {
                    targets: element,
                    y: { from: -100, to: element.y },
                    scale: { from: 0, to: 1 },
                    alpha: { from: 0, to: 1 },
                    duration: 360,
                    ease: 'Back.easeOut'
                }

            }
            this.scene.tweens.add(setting);
        });



        this.setVisible(true);
        this.setActive(true);
    }

    // Метод для скрытия модального окна
    hideModal() {
        this.animatedElements.forEach((element) => {
            var setting;
            if (element.elementId === 'container' || element.elementId === 'button') {
                setting = {
                    targets: element,
                    y: { from: element.y, to: -100 },
                    scale: { from: .9, to: 0 },
                    alpha: { from: 1, to: 0 },
                    duration: 360,
                    ease: 'Back.easeOut',
                    onComplete: () => {
                        this.setVisible(false);
                        this.setActive(false);
                    }
                }

            } else if (element.elementId === 'background') {
                setting = {
                    targets: element,
                    alpha: { from: 1, to: 0 },
                    duration: 360,
                    delay: 160,
                    ease: 'Cubic.easeInOut',
                    onComplete: () => {
                        this.setVisible(false);
                        this.setActive(false);
                    }
                }

            } else {
                setting = {
                    targets: element,
                    y: { from: element.y, to: -100 },
                    scale: { from: 1, to: 0 },
                    alpha: { from: 1, to: 0 },
                    duration: 360,
                    ease: 'Back.easeOut',
                    onComplete: () => {
                        this.setVisible(false);
                        this.setActive(false);
                    }
                }

            }
            this.scene.tweens.add(setting);
        });
    }
}

class ModalHint extends Phaser.GameObjects.Container {
    constructor(scene, image, text_title, text_subtitle, text_text, textButton) {
        super(scene);

        this.animatedElements = [];

        let style_title = {
            fontSize: '64px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
        };

        let style_subtitle = {
            fontSize: '40px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
            wordWrap: { width: 430, useAdvancedWrap: true },
            align: 'center'
        };

        let style_text = {
            fontSize: '26px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
            wordWrap: { width: 500, useAdvancedWrap: true },
            align: 'center'
        };

 
        // Фон модального окна
        const background = scene.add.graphics({ fillStyle: { color: '0x0F0F59', alpha: .8 } });
        background.fillRect(0, 0, scene.cameras.main.width, scene.cameras.main.height);
        background.setDepth(100);
        this.add(background);
        background.elementId = 'background'; // Добавляем пользовательское свойство


        var container = scene.add.sprite(scene.cameras.main.width / 2, 105, image);
        container.elementId = 'container'; // Добавляем пользовательское свойство
        container.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        container.setScale(.9);
        container.setDepth(101);
        this.add(container);

        let title = scene.add.text((scene.cameras.main.width / 2), 200, text_title, style_title);
        title.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        title.setShadow(2, 2, 'rgba(0,0,0,0.4)', 2);
        title.setDepth(102);
        this.add(title);

        var subtitle = scene.add.text(scene.cameras.main.width / 2, 600, text_subtitle, style_subtitle);
        subtitle.setDepth(102);
        subtitle.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        subtitle.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
        this.add(subtitle); // Добавляем иконку в контейнер
        this.animatedElements.push(subtitle);

        let text = scene.add.text(scene.cameras.main.width / 2, 710, text_text, style_text);
        text.setDepth(102);
        text.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        text.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
        this.add(text); // Добавляем иконку в контейнер
        this.animatedElements.push(text);

        var button = new Button(scene, scene.cameras.main.width / 2, 920, textButton, () => {
            this.hideModal()
        }, 'normal');
        button.elementId = 'button'; // Добавляем пользовательское свойство
        button.setScale(.9);
        button.setDepth(102);
        this.add(button);
        this.animatedElements.push(button);

        this.animatedElements.push(container, title, background);

        // Делаем модальное окно интерактивным
        this.setInteractive(new Phaser.Geom.Rectangle(0, 0, scene.cameras.main.width, scene.cameras.main.height), Phaser.Geom.Rectangle.Contains);
        // Добавляем модальное окно в сцену
        scene.add.existing(this);
        this.setVisible(false);
        this.setActive(false);
    }

    // Метод для показа модального окна
    showModal() {

        this.animatedElements.forEach((element) => {
            var setting;
            if (element.elementId === 'container' || element.elementId === 'button') {
                setting = {
                    targets: element,
                    y: { from: -100, to: element.y },
                    scale: { from: 0, to: .9 },
                    alpha: { from: 0, to: 1 },
                    duration: 360,
                    ease: 'Back.easeOut'
                }

            } else if (element.elementId === 'background') {
                setting = {
                    targets: element,
                    alpha: { from: 0, to: 1 },
                    duration: 360,
                    ease: 'Back.easeOut'
                }

            } else {
                setting = {
                    targets: element,
                    y: { from: -100, to: element.y },
                    scale: { from: 0, to: 1 },
                    alpha: { from: 0, to: 1 },
                    duration: 360,
                    ease: 'Back.easeOut'
                }

            }
            this.scene.tweens.add(setting);
        });



        this.setVisible(true);
        this.setActive(true);
    }

    // Метод для скрытия модального окна
    hideModal() {
        this.animatedElements.forEach((element) => {
            var setting;
            if (element.elementId === 'container' || element.elementId === 'button') {
                setting = {
                    targets: element,
                    y: { from: element.y, to: -100 },
                    scale: { from: .9, to: 0 },
                    alpha: { from: 1, to: 0 },
                    duration: 360,
                    ease: 'Back.easeOut',
                    onComplete: () => {
                        this.setVisible(false);
                        this.setActive(false);
                    }
                }

            } else if (element.elementId === 'background') {
                setting = {
                    targets: element,
                    alpha: { from: 1, to: 0 },
                    duration: 360,
                    delay: 160,
                    ease: 'Cubic.easeInOut',
                    onComplete: () => {
                        this.setVisible(false);
                        this.setActive(false);
                    }
                }

            } else {
                setting = {
                    targets: element,
                    y: { from: element.y, to: -100 },
                    scale: { from: 1, to: 0 },
                    alpha: { from: 1, to: 0 },
                    duration: 360,
                    ease: 'Back.easeOut',
                    onComplete: () => {
                        this.setVisible(false);
                        this.setActive(false);
                    }
                }

            }
            this.scene.tweens.add(setting);
        });
    }
}

class ModalEnergy extends Phaser.GameObjects.Container {
    constructor(scene, image, text_title, text_subtitle, text_text, textButton) {
        super(scene);

        this.animatedElements = [];

        let style_title = {
            fontSize: '64px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
        };

        let style_subtitle = {
            fontSize: '32px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
            wordWrap: { width: 530, useAdvancedWrap: true },
            align: 'center'
        };


 
        // Фон модального окна
        const background = scene.add.graphics({ fillStyle: { color: '0x0F0F59', alpha: .8 } });
        background.fillRect(0, 0, scene.cameras.main.width, scene.cameras.main.height);
        background.setDepth(100);
        this.add(background);
        background.elementId = 'background'; // Добавляем пользовательское свойство


        var container = scene.add.sprite(scene.cameras.main.width / 2, 105, image);
        container.elementId = 'container'; // Добавляем пользовательское свойство
        container.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        container.setScale(.9);
        container.setDepth(101);
        this.add(container);

        let title = scene.add.text((scene.cameras.main.width / 2), 150, text_title, style_title);
        title.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        title.setShadow(2, 2, 'rgba(0,0,0,0.4)', 2);
        title.setDepth(102);
        this.add(title);

        var subtitle = scene.add.text(scene.cameras.main.width / 2, 550, text_subtitle, style_subtitle);
        subtitle.setDepth(102);
        subtitle.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        subtitle.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
        this.add(subtitle); // Добавляем иконку в контейнер
        this.animatedElements.push(subtitle);

        var button = new Button(scene, scene.cameras.main.width / 2, subtitle.y + 180, textButton, () => {
            window.location.href = '/' + localStorage.getItem('locale') + '/profile';
        }, 'normal');
        button.elementId = 'button'; // Добавляем пользовательское свойство
        button.setScale(.9);
        button.setDepth(102);
        this.add(button);
        this.animatedElements.push(button);

        const btnClose = scene.add.sprite(scene.cameras.main.width / 1.21, 90, 'btnClose')
        btnClose.setDepth(103);
        btnClose.setOrigin(0, 0);
        btnClose.setInteractive(); // делаем кнопку интерактивной
        btnClose.on('pointerdown', function () {
            window.location.href = '/' + localStorage.getItem('locale') + '/profile';
        });
        this.add(btnClose);
        this.animatedElements.push(btnClose);

        const button2 = new ButtonBorder(scene, scene.cameras.main.width / 2, button.y + 120, scene.langdata.btn_friend, () => {
            window.location.href = '/' + localStorage.getItem('locale') + '/profile';
        }, 'border');
        button2.elementId = 'button'; // Добавляем пользовательское свойство
        button2.setScale(.9);
        button2.setDepth(102);
        this.add(button2);
        this.animatedElements.push(button2);

        this.animatedElements.push(container, title, background);

        // Делаем модальное окно интерактивным
        this.setInteractive(new Phaser.Geom.Rectangle(0, 0, scene.cameras.main.width, scene.cameras.main.height), Phaser.Geom.Rectangle.Contains);
        // Добавляем модальное окно в сцену
        scene.add.existing(this);
        this.setVisible(false);
        this.setActive(false);
    }

    // Метод для показа модального окна
    showModal() {

        this.animatedElements.forEach((element) => {
            var setting;
            if (element.elementId === 'container' || element.elementId === 'button') {
                setting = {
                    targets: element,
                    y: { from: -100, to: element.y },
                    scale: { from: 0, to: .9 },
                    alpha: { from: 0, to: 1 },
                    duration: 360,
                    ease: 'Back.easeOut'
                }

            } else if (element.elementId === 'background') {
                setting = {
                    targets: element,
                    alpha: { from: 0, to: 1 },
                    duration: 360,
                    ease: 'Back.easeOut'
                }

            } else {
                setting = {
                    targets: element,
                    y: { from: -100, to: element.y },
                    scale: { from: 0, to: 1 },
                    alpha: { from: 0, to: 1 },
                    duration: 360,
                    ease: 'Back.easeOut'
                }

            }
            this.scene.tweens.add(setting);
        });



        this.setVisible(true);
        this.setActive(true);
    }

    // Метод для скрытия модального окна
    hideModal() {
        this.animatedElements.forEach((element) => {
            var setting;
            if (element.elementId === 'container' || element.elementId === 'button') {
                setting = {
                    targets: element,
                    y: { from: element.y, to: -100 },
                    scale: { from: .9, to: 0 },
                    alpha: { from: 1, to: 0 },
                    duration: 360,
                    ease: 'Back.easeOut',
                    onComplete: () => {
                        this.setVisible(false);
                        this.setActive(false);
                    }
                }

            } else if (element.elementId === 'background') {
                setting = {
                    targets: element,
                    alpha: { from: 1, to: 0 },
                    duration: 360,
                    delay: 160,
                    ease: 'Cubic.easeInOut',
                    onComplete: () => {
                        this.setVisible(false);
                        this.setActive(false);
                    }
                }

            } else {
                setting = {
                    targets: element,
                    y: { from: element.y, to: -100 },
                    scale: { from: 1, to: 0 },
                    alpha: { from: 1, to: 0 },
                    duration: 360,
                    ease: 'Back.easeOut',
                    onComplete: () => {
                        this.setVisible(false);
                        this.setActive(false);
                    }
                }

            }
            this.scene.tweens.add(setting);
        });
    }
}

/** 
 * Удаляет совпадающие конфеты с игрового поля.
 * @param {Array} matches - массив совпадений для удаления.
 * @param {Phaser.Scene} scene - сцена, в которой происходит удаление.
 */
function removeMatches(matches, scene) {
    const bombPositions = matches.filter(match => match.type === 'bomb');
    const rocketPositions = matches.filter(match => match.type === 'rocket');
    const lightningPositions = matches.filter(match => match.type === 'lightning');

    const offsetX = scene.offsetX; // Пример смещения по X
    // Удаляем все совпадения, кроме тех, которые станут бомбами
    matches.forEach(match => {
        if (match.type !== 'bomb' || match.type !== 'rocket'|| match.type !== 'lightning') {
            let { x, y } = match;
            let candy = scene.grid[y][x];
            if (candy) {

                // Создание спрайта анимации взрыва в позиции бомбы
                let explosion = scene.add.sprite(x * scene.СandySize + offsetX, y * scene.СandySize + scene.offsetY, 'explosion2').setScale(scene.explosionSpriteSize);
                explosion.play('explode2');

                // Уничтожение спрайта анимации после завершения
                explosion.on('animationcomplete', () => { });
                
                playDisappearAnimation(candy.sprite, () => {
                    explosion.destroy();
                    shiftCandiesDown(scene);

                });

                scene.grid[y][x] = null;
                scene.score += 3;

                if (candy.color === 'candy5') {
                    updateMovesUI(scene, 'plus'); // Увеличиваем количество шагов
                }


                // Проверяем, не превышено ли количество собранных конфет
                if (targetCandies[candy.color] && targetCandies[candy.color].collected < targetCandies[candy.color].required) {
                    targetCandies[candy.color].collected++;
                }

            }
        }
    });

   // Создаем бомбы
    bombPositions.forEach(bomb => {
        createBomb(bomb.x, bomb.y, scene);

        if (loggingEnabled) {
            console.log(`Бомба создана в (${bomb.x}, ${bomb.y})`);
        }
    });

    lightningPositions.forEach(lightning => {
        createLightningCandy(lightning.x, lightning.y, scene);

        if (loggingEnabled) {
            console.log(`Молния создана в (${bomb.x}, ${bomb.y})`);
        }
    });

    rocketPositions.forEach(rocket => {
        createRocket(rocket.x, rocket.y, scene, rocket.direction);
        if (loggingEnabled) {
            console.log(`Ракета создана в (${rocket.x}, ${rocket.y}) ${rocket.direction}`);
        }
    });



 

    updateScoreUI(scene);
    updateCollectedCandyUI(scene);
    checkLevelCompletion(scene);

}

class RocketCandy extends Candy {
    constructor(scene, x, y, direction) {
        super(scene, x, y, 'rocket');
        this.rocketType = direction; // 'horizontal' или 'vertical'
        this.createCandySprite();
    }

    createCandySprite() {
        super.createCandySprite();
        this.sprite.setTexture('rocket');
        this.sprite.setScale(0.1);

        if (this.rocketType === 'horizontal') {
            this.sprite.setRotation(Math.PI / 2); // Повернуть спрайт на 90 градусов для вертикальной ракеты
        }
        this.scene.tweens.add({
            targets: this.sprite,
            scaleX: this.scene.СandySpriteSize, // конечный масштаб по X
            scaleY: this.scene.СandySpriteSize, // конечный масштаб по Y
            ease: 'Back.easeOut', // тип анимации, можно выбрать другой
            duration: 500, // продолжительность анимации в миллисекундах
            delay: 300,
        });
    }

    // Метод для взрыва ракеты по горизонтали и вертикали
    explodeCrossRocket() {
        if (this.hasExploded) return;

        this.hasExploded = true;
        const delayBetweenExplosions = 40; // Задержка в миллисекундах

        // Взрыв по горизонтали
        for (let offset = 0; offset < this.gridWidth; offset++) {
            // Взрыв слева от ракеты
            if (this.x - offset >= 0) {
                this.scene.time.delayedCall(delayBetweenExplosions * offset, () => {
                    this.checkAndExplodeSpecialCandy(this.y, this.x - offset);
                });
            }
            // Взрыв справа от ракеты
            if (this.x + offset < this.gridWidth) {
                this.scene.time.delayedCall(delayBetweenExplosions * offset, () => {
                    this.checkAndExplodeSpecialCandy(this.y, this.x + offset);
                });
            }
        }

        // Взрыв по вертикали
        for (let offset = 0; offset < this.gridHeight; offset++) {
            // Взрыв сверху от ракеты
            if (this.y - offset >= 0) {
                this.scene.time.delayedCall(delayBetweenExplosions * offset, () => {
                    this.checkAndExplodeSpecialCandy(this.y - offset, this.x);
                });
            }
            // Взрыв снизу от ракеты
            if (this.y + offset < this.gridHeight) {
                this.scene.time.delayedCall(delayBetweenExplosions * offset, () => {
                    this.checkAndExplodeSpecialCandy(this.y + offset, this.x);
                });
            }
        }

        // Взрыв конфеты на пересечении
        this.scene.time.delayedCall(delayBetweenExplosions * Math.max(this.gridWidth, this.gridHeight), () => {
            this.checkAndExplodeSpecialCandy(this.y, this.x);
        });

        // Задержка для сдвига конфет вниз после взрывов
        this.scene.time.delayedCall(delayBetweenExplosions * (this.gridWidth + this.gridHeight), () => {
            shiftCandiesDown(this.scene);
        });

    }


    explodeRocket() {
        if (this.hasExploded) return;
    
        this.hasExploded = true;
        const delayBetweenExplosions = 80;
        this.scene.score += 100; // Некоторое значение очков за уничтоженную конфет

        // Проверяем, вертикальная ли это ракета
        if (this.rocketType === 'horizontal') {
            // Взрываем конфеты по вертикали от позиции ракеты
            for (let offset = 0; offset < this.gridHeight; offset++) {
                // Взрываем конфеты выше ракеты
                if (this.y - offset >= 0) {
                    this.scene.time.delayedCall(delayBetweenExplosions * offset, () => {
                        this.checkAndExplodeSpecialCandy(this.y - offset, this.x);
                    });
                }
                // Взрываем конфеты ниже ракеты
                if (this.y + offset < this.gridHeight) {
                    this.scene.time.delayedCall(delayBetweenExplosions * offset, () => {
                        this.checkAndExplodeSpecialCandy(this.y + offset, this.x);
                    });
                }
            }
        }
        // Проверяем, горизонтальная ли это ракета
        else if (this.rocketType === 'vertical') {
            // Взрываем конфеты по горизонтали от позиции ракеты
            for (let offset = 0; offset < this.gridWidth; offset++) {
                // Взрываем конфеты слева от ракеты
                if (this.x - offset >= 0) {
                    this.scene.time.delayedCall(delayBetweenExplosions * offset, () => {
                        this.checkAndExplodeSpecialCandy(this.y, this.x - offset);
                    });
                }
                // Взрываем конфеты справа от ракеты
                if (this.x + offset < this.gridWidth) {
                    this.scene.time.delayedCall(delayBetweenExplosions * offset, () => {
                        this.checkAndExplodeSpecialCandy(this.y, this.x + offset);
                    });
                }
            }
        }
        updateCollectedCandyUI(this.scene);
        checkLevelCompletion(this.scene);

        // Сдвигаем конфеты вниз после взрывов
        this.scene.time.delayedCall(delayBetweenExplosions * Math.max(this.gridWidth, this.gridHeight), () => {
            shiftCandiesDown(this.scene);
        });
    }
    
    

    checkAndExplodeSpecialCandy(y, x) {
        let candy = this.grid[y][x];
        if (candy) {

            let explosion = this.scene.add.sprite(candy.sprite.x, candy.sprite.y, 'explosion2').setScale(this.scene.explosionSpriteSize);
            explosion.setDepth(10);
            explosion.play('explode2');

            // Уничтожение спрайта анимации после завершения
            explosion.on('animationcomplete', () => {
                explosion.destroy();
            });

            if (candy instanceof FrozenCandy) {
                // Особая обработка только для взрыва ракеты
                checkAndUnfreezeCandy(x, y, this.scene.levelData, this.scene.grid);
                return;
            }

            if (candy.color === 'candy5') {
                updateMovesUI(this.scene, 'plus'); // Увеличиваем количество шагов
            }

            playDisappearAnimation(candy.sprite, () => {
                this.scene.score += 15; // Некоторое значение очков за уничтоженную конфет
                // Проверяем, не превышено ли количество собранных конфет
                if (targetCandies[candy.color] && targetCandies[candy.color].collected < targetCandies[candy.color].required) {
                    targetCandies[candy.color].collected++;
                }
            });

            // Проверяем, является ли конфета бомбой или ракетой и вызываем соответствующий метод
            if (candy instanceof BombCandy) {
                candy.explodeBomb(); // Здесь нужно определить логику взрыва бомбы
            } else if (candy instanceof RocketCandy && typeof candy.explodeRocket === 'function') {
                candy.explodeRocket(); // Взрыв ракеты, если метод существует
            }


           this.grid[y][x] = null;

        }

    }
}

// Функция для создания бомбы
function createRocket(x, y, scene, direction) {
    const rocketCandy = new RocketCandy(scene, x, y, direction);
    rocketCandy.enableSwipe();
    scene.grid[y][x] = rocketCandy;

    if (loggingEnabled) {
        console.log(`Ракета ${direction} создана в позиции (${x}, ${y})`); // Лог для проверки создания бомбы
    }
}

class Score extends Phaser.GameObjects.Container {
    constructor(scene, x, y) {
        super(scene, x, y);



        let style_title = {
            fontSize: '28px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
        };

        let style_score = {
            fontSize: '100px',
            fill: '#fff',
            fontStyle: 'bold',
            fontFamily: 'AVENGEANCE',
        };
        // Анимация Tween для числа
        let scoreAnim = { score: 0 };
        var savedState = loadGameState();

        let title = scene.add.text(scene.cameras.main.width / 2, 418, scene.langdata.modal_score, style_title);
        title.setDepth(102);
        title.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        title.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
        this.add(title); // Добавляем иконку в контейнер

        let score = scene.add.text((scene.cameras.main.width / 2), title.y + 40, 0, style_score);
        score.setDepth(102);
        score.setOrigin(0.5, 0); // Устанавливаем начало координат текста в его центр по горизонтали
        score.setShadow(1, 1, 'rgba(0,0,0,0.4)', 2);
        this.add(score); // Добавляем иконку в контейнер

            scene.tweens.add({
                targets: scoreAnim,
                score: scene.coinWin, // Значение, к которому анимируется свойство
                duration: 1000, // Продолжительность анимации
                delay: 260,
                ease: 'Sine.easeInOut', // Тип анимации
                onUpdate: function () {
                    score.setText(Math.round(scoreAnim.score).toString());
                }
            });
        
            this.scene.add.existing(this); // Добавляем контейнер в сцену
        }

    updateScore(savedScore) {
            this.scene.tweens.add({
                    targets: this.scoreAnim,
                    score: savedScore, // Значение, к которому анимируется свойство
                    duration: 1000, // Продолжительность анимации
                    delay: 260,
                    ease: 'Sine.easeInOut', // Тип анимации
                    onUpdate: function () {
                        score.setText(Math.round(scoreAnim.score).toString());
                    }
                });
        }
    }

    // Функция для поиска и анимации подсказки
function showHint(scene) {
    let hintCandy = findHint(scene);
    console.log(hintCandy);

    if (hintCandy) {
        // Создать анимацию для подсказки
        scene.tweens.add({
            targets: hintCandy.sprite,
            yoyo: true,
            scale: .8,
            duration: 300,
            repeat: 3,
            ease: 'Sine.easeInOut',
        });
    }
}


// Функция для поиска конфеты, которую можно свайпнуть для создания матча
function findHint(scene) {
    const grid = scene.grid;

    // Вычисляем gridHeight и gridWidth
    const gridHeight = scene.levelData.tiles.length;
    const gridWidth = scene.levelData.tiles[0].length; // Предполагаем, что все строки имеют одинаковую длину

    for (let y = 0; y < gridHeight; y++) {
        for (let x = 0; x < gridWidth; x++) {
            const result = checkSwipeMatch(scene, x, y);
            if (result) {
                if (loggingEnabled) {
                    console.log(`Hint found at (${x}, ${y})`);
                }
                const { swipedCandyX, swipedCandyY } = result;
                return grid[swipedCandyY][swipedCandyX];
            }
        }
    }
    if (loggingEnabled) {
        console.log("No hint found");
    }
    return null;
}




function checkSwipeMatch(scene, x, y) {

    const gridHeight = scene.levelData.tiles.length;
    const gridWidth = scene.levelData.tiles[0].length; // Предполагаем, что все строки имеют одинаковую длину
    let grid = scene.grid;
    if (loggingEnabled) {
        console.log(`Проверка свайпа для координат (${x}, ${y})`);
    }
    // Проверяем, находится ли конфета внутри сетки
    if (x < 0 || x >= gridWidth || y < 0 || y >= gridHeight || !grid[y][x]) {
        console.log('Конфета вне сетки или не существует');
        return false;
    }

    if (grid[y][x].isFrozen) {
        if (loggingEnabled) {
            console.log('Конфета заморожена');
        }
        return false;
    }

    const candyColor = grid[y][x].color;
    console.log(`Цвет текущей конфеты: ${candyColor}`);


    // Проверка свайпа влево
    if (x > 0) {
        if (loggingEnabled) {
            console.log('Проверка свайпа влево');
        }
        // Две конфеты справа
        if (x < gridWidth - 2 && grid[y][x + 1] && grid[y][x + 2] &&
            !grid[y][x + 1].isFrozen && !grid[y][x + 2].isFrozen &&
            grid[y][x + 1].color === candyColor && grid[y][x + 2].color === candyColor) {
            if (loggingEnabled) {
                console.log('Две конфеты справа');
            }
            return { swipedCandyX: x - 1, swipedCandyY: y };
        }

        // Одна конфета справа и одна через позицию
        if (x < gridWidth - 3 && grid[y][x + 1] && grid[y][x + 3] &&
            !grid[y][x + 1].isFrozen && !grid[y][x + 3].isFrozen &&
            grid[y][x + 1].color === candyColor && grid[y][x + 3].color === candyColor) {
            if (loggingEnabled) {
                console.log('Одна конфета справа и одна через позицию');
            }
            return { swipedCandyX: x - 1, swipedCandyY: y };
        }

        // Одна конфета слева и одна через позицию справа
        if (x > 1 && grid[y][x - 2] && grid[y][x + 1] &&
            !grid[y][x - 2].isFrozen && !grid[y][x + 1].isFrozen &&
            grid[y][x - 2].color === candyColor && grid[y][x + 1].color === candyColor) {
            if (loggingEnabled) {
                console.log('Одна конфета слева и одна через позицию справа');
            }
            return { swipedCandyX: x - 2, swipedCandyY: y };
        }

        // Одна конфета сверху и одна через позицию снизу
        if (y > 1 && grid[y - 2][x] && grid[y + 1][x] &&
            !grid[y - 2][x].isFrozen && !grid[y + 1][x].isFrozen &&
            grid[y - 2][x].color === candyColor && grid[y + 1][x].color === candyColor) {
            console.log('Одна конфета сверху и одна через позицию снизу');
            return { swipedCandyX: x - 1, swipedCandyY: y - 2 };
        }

        // Две одноцветные конфеты сверху
        if (y > 1 && grid[y - 1][x - 1] && grid[y - 2][x - 1] &&
            grid[y - 1][x - 1].color === candyColor && grid[y - 2][x - 1].color === candyColor) {
            console.log('Две одноцветные конфеты сверху');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Две одноцветные конфеты снизу
        if (y < gridHeight - 2 && grid[y + 1][x - 1] && grid[y + 2][x - 1] &&
            grid[y + 1][x - 1].color === candyColor && grid[y + 2][x - 1].color === candyColor) {
            console.log('Две одноцветные конфеты снизу');
            return { swipedCandyX: x, swipedCandyY: y };
        }
    }

    // Проверка свайпа вправо
    if (x < gridWidth - 1) {
        console.log('Проверка свайпа вправо');

        // Две конфеты слева
        if (x > 1 && grid[y][x - 1] && grid[y][x - 2] &&
            grid[y][x - 1].color === candyColor && grid[y][x - 2].color === candyColor) {
            console.log('Проверка свайпа вправо');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Одна конфета слева и одна через позицию
        if (x > 2 && grid[y][x - 1] && grid[y][x - 3] &&
            grid[y][x - 1].color === candyColor && grid[y][x - 3].color === candyColor) {
            console.log('Одна конфета слева и одна через позицию');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Одна конфета справа и одна через позицию слева
        if (x < gridWidth - 2 && grid[y][x + 2] && grid[y][x - 1] &&
            grid[y][x + 2].color === candyColor && grid[y][x - 1].color === candyColor) {
            console.log('Одна конфета справа и одна через позицию слева');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Две одноцветные конфеты сверху
        if (y > 1 && grid[y - 1][x + 1] && grid[y - 2][x + 1] &&
            grid[y - 1][x + 1].color === candyColor && grid[y - 2][x + 1].color === candyColor) {
            console.log('Две одноцветные конфеты сверху');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Две одноцветные конфеты снизу
        if (y < gridHeight - 2 && grid[y + 1][x + 1] && grid[y + 2][x + 1] &&
            grid[y + 1][x + 1].color === candyColor && grid[y + 2][x + 1].color === candyColor) {
            console.log('Две одноцветные конфеты снизу');
            return { swipedCandyX: x, swipedCandyY: y };
        }
    }

    // Проверка свайпа вверх
    if (y > 0) {
        console.log('Проверка свайпа вверх');
        // Две конфеты снизу
        if (y < gridHeight - 2 && grid[y + 1][x] && grid[y + 2][x] &&
            grid[y + 1][x].color === candyColor && grid[y + 2][x].color === candyColor) {
            console.log('Две конфеты снизу');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Одна конфета снизу и одна через позицию
        if (y < gridHeight - 3 && grid[y + 1][x] && grid[y + 3][x] &&
            grid[y + 1][x].color === candyColor && grid[y + 3][x].color === candyColor) {
            console.log('Одна конфета снизу и одна через позицию');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Одна конфета сверху и одна через позицию снизу
        if (y > 1 && grid[y - 2][x] && grid[y + 1][x] &&
            grid[y - 2][x].color === candyColor && grid[y + 1][x].color === candyColor) {
            console.log('Одна конфета сверху и одна через позицию снизу');
            return { swipedCandyX: x, swipedCandyY: y - 2 };
        }

        // Две одноцветные конфеты слева
        if (x > 1 && grid[y - 1][x - 1] && grid[y - 1][x - 2] &&
            grid[y - 1][x - 1].color === candyColor && grid[y - 1][x - 2].color === candyColor) {
            console.log('Две одноцветные конфеты слева');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Две одноцветные конфеты справа
        if (x < gridWidth - 2 && grid[y - 1][x + 1] && grid[y - 1][x + 2] &&
            grid[y - 1][x + 1].color === candyColor && grid[y - 1][x + 2].color === candyColor) {
            console.log('Две одноцветные конфеты справа');
            return { swipedCandyX: x, swipedCandyY: y };
        }
    }

    // Проверка свайпа вниз
    if (y < gridHeight - 1) {
        console.log('Проверка свайпа вниз');
        // Две конфеты сверху
        if (y > 1 && grid[y - 1][x] && grid[y - 2][x] &&
            grid[y - 1][x].color === candyColor && grid[y - 2][x].color === candyColor) {
            console.log('Две конфеты сверху');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Одна конфета сверху и одна через позицию
        if (y > 2 && grid[y - 1][x] && grid[y - 3][x] &&
            grid[y - 1][x].color === candyColor && grid[y - 3][x].color === candyColor) {
            console.log('Одна конфета сверху и одна через позицию');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Одна конфета снизу и одна через позицию сверху
        if (y > 0 && y < gridHeight - 2 && grid[y + 2][x] && grid[y - 1][x] &&
            grid[y + 2][x].color === candyColor && grid[y - 1][x].color === candyColor) {
            console.log('Одна конфета снизу и одна через позицию сверху');
            return { swipedCandyX: x, swipedCandyY: y - 2 };
        }

        // Две одноцветные конфеты слева
        if (x > 1 && grid[y + 1][x - 1] && grid[y + 1][x - 2] &&
            grid[y + 1][x - 1].color === candyColor && grid[y + 1][x - 2].color === candyColor) {
            console.log('Две одноцветные конфеты слева');
            return { swipedCandyX: x, swipedCandyY: y };
        }

        // Две одноцветные конфеты справа
        if (x < gridWidth - 2 && grid[y + 1][x + 1] && grid[y + 1][x + 2] &&
            grid[y + 1][x + 1].color === candyColor && grid[y + 1][x + 2].color === candyColor) {
            console.log('Две одноцветные конфеты справа');
            return { swipedCandyX: x, swipedCandyY: y };
        }
    }

    console.log('Нет подходящих свайпов');
    return false;
}

function onTimer(scene) {
    if (scene.levelData.timeLeft != '0') {
        scene.timeText = scene.add.text(290, 10, 'Время: ' + levelData.timeLeft, { fontSize: '32px', fill: '#000' });

        scene.timeEvent = scene.time.addEvent({
            delay: 1000,
            callback: onTimerUpdate,
            callbackScope: scene,
            loop: true
        });
    }
}
/**
 * Обновляет таймер игры и обрабатывает логику окончания игры по времени.
 */
function onTimerUpdate() {
    if (scene.levelData.timeLeft != '0') {
        scene.levelData.timeLeft--;
        this.timeText.setText('Время: ' + scene.levelData.timeLeft);
        if (scene.levelData.timeLeft <= 0) {
            this.timeEvent.remove();

            // Логика завершения игры
            alert("Время вышло");
        }
    }
}


var gk = 'WsF0fym40tdWZcMX22Au1LvSdEkHfNUB';

/**
 * Обновляет интерфейс с текущим счетом пользователя.
 * @param {Phaser.Scene} scene - Сцена, в которой отображается счет.
 */
function updateScoreUI(scene) {

    let newScore = scene.score; // новое значение очков из глобальной переменной score
    scene.tweens.add({
        targets: { value: scene.currentScore },
        value: newScore,
        duration: 500, // продолжительность анимации в миллисекундах
        ease: 'Sine.easeInOut', // тип анимации
        onUpdate: function (tween) {
            const val = Math.round(tween.getValue());
            scene.scoreText.setText(val);
        },
        onComplete: function () {
            scene.currentScore = newScore; // обновляем текущий счет после анимации

        }
    });
    var gameState = {
        score: newScore,
    };

    saveGameState(gameState);
}


function saveGameState(newState) {

    var savedState = localStorage.getItem('localeData');

    if (savedState !== null) {
        savedState = CryptoJS.AES.decrypt(savedState, gk);
        savedState = savedState.toString(CryptoJS.enc.Utf8);
    }

    var gameState = savedState ? JSON.parse(savedState) : {};

    for (var key in newState) {
        gameState[key] = newState[key];
    }
    const data = JSON.stringify(gameState);

    const encryptedData = CryptoJS.AES.encrypt(data, gk).toString();
    localStorage.setItem('localeData', encryptedData);

}

function loadGameState() {
    const encryptedData = localStorage.getItem('localeData');

    if (!encryptedData) {
        return null;
    }

    try {
        const bytes = CryptoJS.AES.decrypt(encryptedData, gk);
        const originalData = bytes.toString(CryptoJS.enc.Utf8);
        return JSON.parse(originalData);

    } catch (e) {
        console.error('Не удалось расшифровать данные:', e);
        return null;
    }
}
/**
 * Обновляет интерфейс с оставшимися ходами пользователя.
 */
function updateMovesUI(scene, type) {
    if (type === 'plus') {
        scene.levelData.movesLeft++;
    } else {
        scene.levelData.movesLeft--;
    }
    scene.movesText.setText(scene.levelData.movesLeft);

    var gameState = {
        movesLeft: scene.levelData.movesLeft,
    };

    saveGameState(gameState);

    if (scene.levelData.movesLeft < 0) {
        alert("Закончились ходы!");
        setTimeout(() => {
            window.location.href = '/game';
        }, 300);
    }
}

// Функция для удаления определенного свойства из состояния игры
function removePropertyFromGameState(property) {
    // Загружаем текущее сохраненное состояние
    var savedState = localStorage.getItem('localeData');
    if (savedState !== null) {
        savedState = CryptoJS.AES.decrypt(savedState, gk);
        savedState = savedState.toString(CryptoJS.enc.Utf8);
    }

    var gameState = savedState ? JSON.parse(savedState) : {};

    // Проверяем, существует ли свойство и удаляем его
    if (gameState.hasOwnProperty(property)) {
        delete gameState[property];

        // Сохраняем обновленное состояние обратно в localStorage
        const data = JSON.stringify(gameState);
        const encryptedData = CryptoJS.AES.encrypt(data, gk).toString();
        localStorage.setItem('localeData', encryptedData);
    }
}

function checkLevelCompletion(scene) {
    let allTargetsMet = true;
    for (let candyType in targetCandies) {
        if (targetCandies[candyType].collected < targetCandies[candyType].required) {
            allTargetsMet = false;
            break;
        }
    }

    if (allTargetsMet) {
        if (activeTweenCount === 0) {
            var gameState = {
                finish: true,
            };

            saveGameState(gameState);

            var dataGame = {
                userId: scene.userId,
                level: scene.levelNumber,
                score: scene.score,
                time: 0,
                finish: true,
            };
            endGame(dataGame);

            scene.levelData.userCoins += scene.coinWin;
            scene.levelData.userEnergy--;
            setTimeout(() => {
                scene.endModal.showModal();

                var savedState = loadGameState();
                if (savedState.finish == true) {

                } else {
                    var gameState = {
                        level: scene.levelNumber + 1,
                    };
                    saveGameState(gameState);
                }
            }, 300);
        }

        // Останавливаем игру или переходим к следующему уровню
    }
}



function icinstantGift(scene) {
    // Определите минимальное и максимальное количество шагов
    const minSteps = 1;
    const maxSteps = 3;
    scene.totalSteps = scene.totalSteps + 1;

    // Если случайное количество шагов еще не определено, выберите его
    if (scene.randomSteps == null) {
        scene.randomSteps = Math.floor(Math.random() * (maxSteps - minSteps + 1)) + minSteps;
    }

    // Показать модальное окно, если достигнуто случайное количество шагов
    if (scene.totalSteps === scene.randomSteps) {
        console.log(scene.randomSteps)

        if (scene.instantGift === "box" || scene.instantGift === "mobi") {
            scene.giftModal.showModal();
            var dataPrize = {
                userId: scene.userId,
            };
            lotteryGame(dataPrize);
            // Сбросить случайное количество шагов для следующего цикла
            scene.randomSteps = 0;
        }
    }
}