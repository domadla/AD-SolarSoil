# Fragmentation
1. Create Code Fragments first in the:
- `components`
- `components/templates`
- `handlers`
- `utils`
2. always include at the top the code the following:
- `require BASE_PATH . '/vendor/autoload.php';`
- `require BASE_PATH . '/utils/htmlEscape.utils.php';`
3. To use the commands below followed by `BASE_PATH` then the directory of wanted fragment:
- `require`
- `require_once`
- `include`
- `include_once`
> ex.: `require_once BASE_PATH . '/vendor/autoload.php';`

# Composer

1. Download [Composer](https://getcomposer.org/download/)
2. Install Composer
3. Check if composer is running
```cmd
composer --version
```
> Note: it should respond with its `php` version and `composer` version
4. Go to your project and Initialize it
```cmd
composer init
```
- for all the the following uestion just enter/confirm/agree to it except: `Package Type`, state it as `project`
5. Create `router.php` and copy code below
```php
<?php
require __DIR__ . '/bootstrap.php';

if (php_sapi_name() === 'cli-server') {
    $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = BASE_PATH . $urlPath;
    if (is_file($file)) {
        return false;
    }
}

require BASE_PATH . '/index.php';
```
6. Create `bootstrap.php` and copy code below
```php
<?php
define('BASE_PATH', realpath(__DIR__));

chdir(BASE_PATH);
```
7. Install vendors
```cmd
composer install
```
8. Run the system with the command below under your terminal. if you don't know how to open or it was hidden use keyboard shortcut keys of `ctrl` + `~`, beside the number 1
```cmd
php -S localhost:8000 router.php
```
> note you can change `8000` if you prefer different port ranges from `3000-9999`.

# Checking work Using Composer
1. Clone Repository
2. Install vendors
```cmd
composer install
```
3. Run the system with the command below under your terminal. if you don't know how to open or it was hidden use keyboard shortcut keys of `ctrl` + `~`, beside the number 1
```cmd
php -S localhost:8000 router.php
```
> you can change `8000` if you prefer different port ranges from `3000-9999`.