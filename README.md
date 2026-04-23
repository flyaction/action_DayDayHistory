# 历史上的今天

一个基于 PHP + MySQL 的"历史上的今天"网站，前端采用 Bootstrap 5 设计，页面美观大方，支持响应式布局。

## 项目结构

```
.
├── config.php      # 数据库配置文件
├── api.php         # 后端数据接口
├── index.php       # 前端主页面
├── dayday.sql      # 历史事件数据（MySQL 导出文件）
└── README.md       # 本说明文件
```

## 环境要求

- PHP >= 7.4（需开启 PDO MySQL 扩展）
- MySQL >= 5.7 或 MariaDB >= 10.2
- Web 服务器（Nginx / Apache / 内置服务器均可）

## 快速部署

### 1. 导入数据库

```bash
mysql -u root -p < dayday.sql
```

或在 phpMyAdmin / Navicat 等工具中导入 `dayday.sql`。

### 2. 修改数据库配置

编辑 `config.php`，填写你的数据库连接信息：

```php
return [
    'host'     => 'localhost',
    'port'     => 3306,
    'database' => 'dayday',
    'username' => 'root',
    'password' => '你的密码',
    'charset'  => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
];
```

### 3. 启动服务

**方式一：PHP 内置服务器（开发测试）**

```bash
php -S localhost:8080
```

然后访问 http://localhost:8080

**方式二：Nginx / Apache（生产环境）**

将项目目录配置为网站根目录即可。确保 PHP 已正确配置。

## 功能说明

- **默认显示今天**：打开页面自动加载当天的历史事件
- **日期切换**：支持日期选择器、左右箭头切换前一天/后一天
- **三类数据展示**：
  - 大事记（蓝色）
  - 出生（绿色）
  - 逝世（灰色）
- **年份显示**：自动识别公元前年份并显示为"公元前XX年"
- **响应式设计**：适配桌面端和移动端

## API 接口

```
GET /api.php?date=MM-DD
```

**参数：**

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

见 `dayday.sql` 中的 `action_history` 表定义。核心字段：

- `show_date` (MM-DD)：用于按固定月日查询
- `year`：年份（支持负数，表示公元前）
- `data_type`：1=大事记, 2=出生, 3=逝世
- `content`：事件描述

## 索引建议（性能优化）

数据量较大时，建议添加以下索引：

```sql
ALTER TABLE `action_history` ADD INDEX `idx_show_date` (`show_date`);
ALTER TABLE `action_history` ADD INDEX `idx_date_type` (`show_date`, `data_type`);
```

## 技术栈

- 后端：PHP + PDO + MySQL
- 前端：HTML5 + Bootstrap 5 + Bootstrap Icons
- 数据：MySQL 导出文件（约 4.7 万条历史事件）

## 开源协议

本项目仅供学习参考使用。
