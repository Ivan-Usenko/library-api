# Огляд
library-api це API для онлайн бібліотеки який дозволяє користувачам переглядати каталог книг і додавати їх в обрані.
Адміністратори бібліотеки за допомогою цього API можуть додавати і видаляти книги, а також експортувати дані про книги.
# Функціонал
* Перегляд каталогу книг
* Перегляд детальної інформації про книгу
* Автентифікація і авторизація користувачів
* Збереження книги в обрані окремо для кожного користувача
* Можливість змінювати ролі користувачів через консольні команди
* Можливість адмінів додавати, видаляти, експортувати книги в `.csv` форматі
# Технології
* `PHP v8.0.30` мова програмування
* `symfony/skeleton v5.8.15` PHP фреймворк
* `PostgreSQL` система керування базами даних
* Система `Git` для контролю версій
* `Twig` рушій шаблонізації HTML сторінок для PHP
# Встановлення для Windows
## Попередні вимоги
1. Встановлений PHP, наприклад за допомогою [XAMPP](https://www.apachefriends.org/download.html)
2. Встановлений [Git](https://git-scm.com/downloads)
3. Встановлений [PostgreSQL](https://www.postgresql.org/download/)
4. Встановлений [symfony/skeleton](https://symfony.com/download)
5. Встановлений [Composer](https://getcomposer.org/)
## Завантаження файлів
Створення папки проєкту
```
mkdir libraryapi
cd libraryapi
```
Ініціалізація папки і завантаження файлів
```
git init
git pull https://github.com/Ivan-Usenko/library-api.git
```
## Встановлення небхідних компонентів
Виконати в терміналі наступні команди
```
composer require symfony/maker-bundle --dev
composer require symfony/orm-pack
composer require symfony/twig-bundle
composer require symfony/security-bundle
composer require form validator
composer require symfony/string
composer require symfony/intl
composer require symfony/serializer
```
## Створення бази даних
В файлі `.env` в рядку
`DATABASE_URL="postgresql://postgres:admin@127.0.0.1:5432/library?serverVersion=16&charset=utf8"`
змінити "postgres:admin" на логін користувача PostgreSQL і його пароль відповідно.

В терміналі виконати команду створення бази
```
symfony console doctrine:database:create
```
Виконати міграції
```
symfony console doctrine:migrations:migrate
```
Запустити локальний сервер
```
symfony server:start
```
Відкрити в браузері локальний сервер [127.0.0.1:8000](http://127.0.0.1:8000)