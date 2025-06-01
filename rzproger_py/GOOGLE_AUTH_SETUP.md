# Настройка Google OAuth - Инструкция

## Что уже сделано:
✅ Настроены конфиги (.env, services.php)  
✅ Создан GoogleController  
✅ Добавлены роуты  
✅ Обновлена модель User  
✅ Добавлены кнопки в формы логина/регистрации  
✅ Создана миграция для google_id  

## Что нужно выполнить:

### 1. Установить Laravel Socialite
Откройте командную строку в папке `rzproger_py` и выполните:
```bash
composer require laravel/socialite
```

### 2. Выполнить миграцию
```bash
php artisan migrate
```

### 3. Добавить тест-пользователя в Google Console
1. Перейдите в Google Cloud Console
2. OAuth consent screen → Test users
3. Добавьте свой email адрес

### 4. Запустить сервер
```bash
php artisan serve
```

## Настройки Google OAuth:
- **Client ID**: 285213639035-3hjcgc3uqogdl7ln0u9dpg4ld5nlp4pd.apps.googleusercontent.com
- **Client Secret**: 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
- **Redirect URI**: http://127.0.0.1:8000/auth/google/callback

## Как протестировать:
1. Перейдите на `/login`
2. Нажмите "Войти через Google"
3. Выберите Google аккаунт
4. Должно перенаправить обратно на сайт и авторизовать

## Возможные проблемы:
- **Error 403: access_denied** - нужно добавить email в тест-пользователи
- **redirect_uri_mismatch** - проверьте URL в Google Console
- **Class Socialite not found** - нужно установить пакет через composer
