<?php
// data_config.php - 游戏数据配置和存储系统

// 安全防护
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    exit('禁止直接访问');
}

// 定义数据存储路径
define('DATA_DIR', __DIR__ . '/data/');
define('PLAYERS_FILE', DATA_DIR . 'players.json');
define('ALLIANCES_FILE', DATA_DIR . 'alliances.json');
define('WORLD_FILE', DATA_DIR . 'world.json');
define('MARKET_FILE', DATA_DIR . 'market.json');
define('EVENTS_FILE', DATA_DIR . 'events.json');
define('MESSAGES_FILE', DATA_DIR . 'messages.json');
define('QUESTS_FILE', DATA_DIR . 'quests.json');

// 游戏配置
$GAME_CONFIG = [
    'version' => '2.0',
    'max_players' => 1000,
    'world_size' => ['width' => 1000, 'height' => 1000],
    'biomes' => ['forest', 'desert', 'mountain', 'ocean', 'swamp', 'arctic', 'volcano', 'cave'],
    'seasons' => ['spring', 'summer', 'autumn', 'winter'],
    'weather' => ['sunny', 'rainy', 'stormy', 'snowy', 'foggy'],
    'day_duration' => 300, // 5分钟一天
    'season_duration' => 7200, // 2小时一季
    'max_alliance_size' => 50,
];

// 资源类型
$RESOURCES = [
    'food' => ['berry', 'meat', 'fish', 'fruit', 'vegetable', 'grain'],
    'material' => ['wood', 'stone', 'iron', 'gold', 'diamond', 'cloth', 'leather', 'herb'],
    'tool' => ['axe', 'pickaxe', 'fishing_rod', 'bow', 'spear', 'knife'],
    'building' => ['shelter', 'campfire', 'storage', 'workshop', 'farm'],
    'special' => ['magic_crystal', 'ancient_relic', 'radioactive_ore', 'alien_artifact']
];

// 技能系统
$SKILLS = [
    'survival' => ['hunting', 'fishing', 'foraging', 'cooking'],
    'crafting' => ['woodworking', 'smithing', 'weaving', 'alchemy'],
    'combat' => ['swords', 'archery', 'magic', 'defense'],
    'exploration' => ['navigation', 'climbing', 'swimming', 'stealth']
];

// 敌对生物
$ENEMIES = [
    'wolf', 'bear', 'zombie', 'skeleton', 'giant_spider', 'dragon',
    'mutant', 'alien', 'ghost', 'bandit', 'boss_monster'
];

// 确保数据目录存在
function initDataDirectory() {
    if (!file_exists(DATA_DIR)) {
        mkdir(DATA_DIR, 0777, true);
    }
    
    // 初始化数据文件
    $files = [
        PLAYERS_FILE => ['players' => [], 'last_update' => time()],
        ALLIANCES_FILE => ['alliances' => [], 'requests' => []],
        WORLD_FILE => [
            'world_map' => [],
            'weather' => 'sunny',
            'season' => 'spring',
            'day' => 1,
            'temperature' => 20,
            'events' => []
        ],
        MARKET_FILE => ['listings' => [], 'history' => []],
        EVENTS_FILE => ['global_events' => [], 'system_log' => []],
        MESSAGES_FILE => ['messages' => [], 'chats' => []],
        QUESTS_FILE => ['daily_quests' => [], 'story_quests' => []]
    ];
    
    foreach ($files as $file => $defaultData) {
        if (!file_exists($file)) {
            file_put_contents($file, json_encode($defaultData, JSON_PRETTY_PRINT));
        }
    }
}

// 生成世界地图
function generateWorldMap($width = 100, $height = 100) {
    $world = [];
    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $biome = getRandomBiome($x, $y);
            $world[$x][$y] = [
                'biome' => $biome,
                'resources' => generateResources($biome),
                'enemies' => rand(0, 3) > 0 ? [] : [getRandomEnemy()],
                'player_id' => null,
                'buildings' => [],
                'explored_by' => [],
                'danger_level' => rand(1, 10)
            ];
        }
    }
    return $world;
}

function getRandomBiome($x, $y) {
    global $GAME_CONFIG;
    $biomes = $GAME_CONFIG['biomes'];
    srand($x * 1000 + $y);
    return $biomes[rand(0, count($biomes) - 1)];
}

function generateResources($biome) {
    global $RESOURCES;
    $resources = [];
    $count = rand(1, 5);
    for ($i = 0; $i < $count; $i++) {
        $type = array_rand($RESOURCES['material']);
        $amount = rand(1, 20);
        $resources[$type] = $amount;
    }
    return $resources;
}
