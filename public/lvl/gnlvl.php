<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Функция для генерации уровней с учетом общего количества уровней
function generateLevel($levelNumber, $totalLevels) {
    $colors = generateColors($levelNumber, $totalLevels);
    $targetCandies = generateTargetCandies($levelNumber, $colors, $totalLevels); // Сначала генерируем targetCandies
    $tiles = generateTiles($levelNumber, $totalLevels); // Генерация tiles
    $movesLeft = calculateMovesLeft($levelNumber, $targetCandies, $totalLevels); // Теперь movesLeft рассчитывается корректно

    $level = [
        "levelNumber" => $levelNumber,
        "targetScore" => 0,
        "movesLeft" => $movesLeft,
        "timeLeft" => 0,
        "tiles" => $tiles,
        "colors" => $colors,
        "targetCandies" => $targetCandies
    ];

    $fileName = 'levels/'.$levelNumber . '.json';
    file_put_contents($fileName, json_encode($level, JSON_PRETTY_PRINT));
}


function generateTiles($levelNumber, $totalLevels) {
    $tilesOptions = [
        // Сложность 1
        '1' => [
            [1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1]
        ],
        // Сложность 2
        '2' => [
            [1, 1, 1, 1, 1, 1, 1],
            [1, 0, 1, 1, 1, 0, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 0, 1, 1, 1, 0, 1],
            [1, 1, 1, 1, 1, 1, 1]
        ],
        // Сложность 3
        '3' => [
            [0, 1, 1, 0, 1, 1, 0],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ],      
        // Сложность 4
        '4' => [
            [0, 1, 0, 0, 0, 1, 0],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 1, 0, 0, 0, 1, 0]
        ],
        // Сложность 5
        '5' => [
            [0, 1, 1, 1, 1, 1, 0],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 1, 1, 1, 1, 1, 0]
        ],
        // Сложность 6
        '6' => [
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 1, 1, 1, 1, 1, 0],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1]
        ],
        // Сложность 7
        '7' => [
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ],
        // Сложность 8
        '8' => [
            [1, 1, 0, 0, 0, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 0, 0, 0, 1, 1]
        ],
        // Сложность 9
        '9' => [
            [0, 1, 1, 0, 1, 1, 0],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ],
        // Сложность 10
        '10' => [
            [0, 1, 1, 0, 1, 1, 0],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [1, 1, 1, 1, 1, 1, 1],
            [0, 1, 1, 1, 1, 1, 0],
            [0, 0, 1, 1, 1, 0, 0],
            [0, 0, 0, 1, 0, 0, 0]
        ]
  
    ];

 // Подсчет количества вариантов tilesOptions
 $optionsCount = count($tilesOptions);

 // Вычисление индекса для выбора варианта tiles на основе прогресса уровня и случайного фактора
 $progress = ($levelNumber % $totalLevels) / $totalLevels;
 $randomFactor = rand(0, 100) / 100;

 // Динамическое распределение шансов выбора в зависимости от количества опций
 $difficultyLevel = 1; // Начальный уровень сложности
 $thresholdIncrement = 1 / $optionsCount; // Равномерное распределение порогов в зависимости от количества опций

 for ($i = 1; $i <= $optionsCount; $i++) {
     if ($randomFactor < ($thresholdIncrement * $i + $progress)) {
         $difficultyLevel = $i;
         break; // Выбор подходящего уровня сложности и выход из цикла
     }
 }

 // Убедитесь, что $difficultyLevel не выходит за пределы доступных опций
 $difficultyLevel = min($difficultyLevel, $optionsCount);

 // Возвращаем выбранный вариант tiles
 return $tilesOptions[strval($difficultyLevel)];
}

function calculateMovesLeft($levelNumber, $targetCandies, $totalLevels) {
    // Определение базового количества ходов
    $baseMoves = 20;

    // Суммирование всех целевых конфет
    $totalTargetCandies = array_sum($targetCandies);

    // Коэффициент сложности, зависящий от разнообразия целевых конфет и общего количества уровней
    $diversityFactor = count($targetCandies) / 4; // Максимум 4 разных цвета
    $levelFactor = ($levelNumber / $totalLevels) + 0.5; // Увеличиваем сложность на более поздних уровнях

    // Расчет количества ходов с учетом сложности и разнообразия
    $movesLeft = round($baseMoves + ($totalTargetCandies / 10) * $diversityFactor * $levelFactor);

    // Гарантируем, что количество ходов не меньше минимального значения и не больше максимального
    return max(15, min($movesLeft, 200));
}


// Пример функции для генерации цветов конфет на доске с учетом общего количества уровней
function generateColors($levelNumber, $totalLevels) {
    $baseColors = ["candy1", "candy2", "candy3", "candy4", "candy6"];
    $additionalColors = floor($levelNumber / 20);
    $colorsCount = min(3 + $additionalColors, count($baseColors));
    shuffle($baseColors);
    return array_slice($baseColors, 0, $colorsCount);
}

// Пример функции generateTargetCandies, которая теперь учитывает общее количество уровней
function generateTargetCandies($levelNumber, $colorsOnBoard, $totalLevels) {
    $targets = [];
    $baseTarget = 10;
    $difficultyFactor = max(1, 100 / $totalLevels);

    // Расчет максимального количества целевых конфет на основе номера уровня
    $maxTargets = min(4, max(2, floor(($levelNumber - 1) / 25) + 2)); // Увеличиваем количество каждые 25 уровней до 4

    // Перемешиваем цвета
    shuffle($colorsOnBoard);

    // Ограничиваем количество цветов до максимально возможного значения
    $colorsCount = min($maxTargets, count($colorsOnBoard));

    // Равномерное распределение дополнительных целей между доступными цветами
    $additionalTarget = floor(($levelNumber / 15) * (5 * $difficultyFactor));
    $baseAdditionalTarget = floor($additionalTarget / $colorsCount);

    foreach ($colorsOnBoard as $color) {
        // Проверяем, не превысили ли мы лимит цветов
        if ($colorsCount <= 0) {
            break;
        }
        $additionalTargetForColor = $baseAdditionalTarget;
        // Добавляем остаток дополнительных целей к первым цветам в списке
        if ($additionalTarget > 0) {
            $additionalTargetForColor++;
            $additionalTarget--;
        }
        $targets[$color] = $baseTarget + $additionalTargetForColor;
        $colorsCount--;
    }

    return $targets;
}




// Основной код для обработки запроса и генерации уровней
$totalLevels = isset($_GET['n']) ? (int)$_GET['n'] : 10; // Получение количества уровней из параметра запроса
$totalLevels = max(1, $totalLevels); // Установка минимума в 1 уровень

for ($i = 1; $i <= $totalLevels; $i++) {
    generateLevel($i, $totalLevels); // Генерация уровней с учетом общего количества
}

echo "Generated $totalLevels levels with dynamic difficulty adjustment.\n";
