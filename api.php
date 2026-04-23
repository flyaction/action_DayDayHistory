<?php
/**
 * API 接口：获取指定日期的历史事件
 * 请求方式：GET
 * 参数：date (格式: MM-DD，如 01-01)
 * 返回：JSON
 */

header('Content-Type: application/json; charset=utf-8');

// 加载配置
$config = require __DIR__ . '/config.php';

// 获取请求参数
$date = isset($_GET['date']) ? $_GET['date'] : date('m-d');

// 简单验证日期格式
if (!preg_match('/^\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['code' => 400, 'message' => '日期格式错误，应为 MM-DD']);
    exit;
}

// 连接数据库
try {
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $config['host'],
        $config['port'],
        $config['database'],
        $config['charset']
    );
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['code' => 500, 'message' => '数据库连接失败：' . $e->getMessage()]);
    exit;
}

// 查询数据
$sql = "SELECT `id`, `year`, `content`, `data_type` 
        FROM `action_history` 
        WHERE `show_date` = :date 
        ORDER BY `year` ASC, `id` ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':date' => $date]);
$rows = $stmt->fetchAll();

// 按类型分组
$result = [
    'code' => 200,
    'date' => $date,
    'events'   => [], // data_type = 1
    'births'   => [], // data_type = 2
    'deaths'   => [], // data_type = 3
];

foreach ($rows as $row) {
    $item = [
        'id'      => (int)$row['id'],
        'year'    => (int)$row['year'],
        'content' => $row['content'],
    ];

    switch ((int)$row['data_type']) {
        case 1:
            $result['events'][] = $item;
            break;
        case 2:
            $result['births'][] = $item;
            break;
        case 3:
            $result['deaths'][] = $item;
            break;
    }
}

// 返回 JSON
echo json_encode($result, JSON_UNESCAPED_UNICODE);
