# 历史上的今天

一个极简风格的"历史上的今天"网站，基于 PHP + MySQL，前端采用 Bootstrap 5。数据涵盖公元前至现代，约 4.7 万条历史事件。

## 项目结构

```
.
├── config.php      # 数据库配置
├── api.php         # 后端数据接口
├── index.php       # 前端主页面
├── dayday.sql      # 历史事件数据（MySQL 导出）
├── .gitignore      # Git 忽略规则
└── README.md
```

## 环境要求

- PHP >= 7.4（需开启 PDO MySQL 扩展）
- MySQL >= 5.7 或 MariaDB >= 10.2
- Web 服务器（Nginx / Apache / PHP 内置服务器均可）

## 快速部署

### 1. 导入数据库

```bash
mysql -u root -p < dayday.sql
```

或在 phpMyAdmin / Navicat 等工具中导入 `dayday.sql`。

### 2. 修改配置

编辑 `config.php`，填写数据库连接信息：

```php
return [
    'host'     => 'localhost',
    'port'     => 3306,
    'database' => 'dayday',
    'username' => 'root',
    'password' => '123456',
    'charset'  => 'utf8mb4',
];
```

### 3. 启动

```bash
php -S localhost:8080
```

访问 http://localhost:8080

## 功能说明

- **默认今天**：打开页面自动加载当天的历史事件
- **日期切换**：左右箭头切换前后一天，点击日期可弹出月日选择器
- **统一列表**：大事记、出生、逝世合并为一个列表，按年份升序排列
- **类型标签**：每条事件旁有小型标签（大事 / 出生 / 逝世）区分类型
- **年份处理**：公元前年份自动显示为"前XX年"
- **响应式**：适配桌面端与移动端
- **底部统计**：显示当前日期收录的事件总数

## API 接口

```
GET /api.php?date=MM-DD
```

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| date | string | 否 | 日期格式 MM-DD，如 01-01。不传则默认今天 |

**返回示例：**

```json
{
    "code": 200,
    "date": "01-01",
    "events": [
        {"id": 1, "year": 1979, "content": "中美正式建交..."}
    ],
    "births": [
        {"id": 150, "year": 1924, "content": "查理·芒格，美国投资家"}
    ],
    "deaths": [
        {"id": 270, "year": 1894, "content": "海因里希·赫兹，德国物理学家..."}
    ]
}
```

## 数据库表结构

`action_history` 表核心字段：

| 字段 | 说明 |
|------|------|
| `show_date` | MM-DD 格式，用于按固定月日查询 |
| `year` | 年份，`int` 类型支持负数（公元前） |
| `data_type` | 1=大事记, 2=出生, 3=逝世 |
| `content` | 事件描述内容 |

## 索引建议

```sql
ALTER TABLE `action_history` ADD INDEX `idx_show_date` (`show_date`);
ALTER TABLE `action_history` ADD INDEX `idx_date_type` (`show_date`, `data_type`);
```

## 技术栈

- 后端：PHP + PDO + MySQL
- 前端：Bootstrap 5 + Bootstrap Icons
- 数据：约 4.7 万条历史事件（来源 Wikipedia）
