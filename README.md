# JaneStore 应用商店示例

一个使用 PHP 和 Materialize 构建的简易应用商店，用户可以投稿软件，管理员审核后展示。

## 功能
- 用户注册、登录
- 投稿应用：填写名称、描述、链接以及可选的截图地址
- 管理员在后台通过或拒绝投稿
- 首页展示已通过的应用，可查看详情并访问链接

## 环境要求
- PHP 7.4 以上，需开启 SQLite 支持

## 启动步骤
1. 安装 PHP
2. 在仓库根目录运行：
   ```bash
   php -S localhost:8000
   ```
   首次运行会在 `data/app_store.db` 创建数据库并生成默认管理员账号 `admin/admin`
3. 访问 `http://localhost:8000`

## 目录结构
- `public/` 前端页面
- `admin/` 管理后台
- `db.php`、`auth.php` 公共代码
- `data/` SQLite 数据库文件
