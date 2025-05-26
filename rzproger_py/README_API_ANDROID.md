# Настройка API Laravel для интеграции с Android-приложением

## Что уже настроено

1. **CORS Middleware**
   - Middleware `Cors` добавлен для обработки Cross-Origin запросов
   - Настроены заголовки `Access-Control-Allow-Origin`, `Access-Control-Allow-Methods`, `Access-Control-Allow-Headers`

2. **API-маршруты**
   - Публичные маршруты для получения списка комнат и аутентификации
   - Защищенные маршруты для работы с бронированиями (требуют авторизации)

3. **Аутентификация**
   - Laravel Sanctum установлен и настроен
   - Реализованы методы регистрации, входа и выхода
   - Токены API генерируются и возвращаются при авторизации

4. **API-контроллеры**
   - `AuthController` - аутентификация и регистрация
   - `RoomController` - работа с комнатами и их типами
   - `BookingController` - создание и управление бронированиями
   - `ExtraServiceController` - получение списка дополнительных услуг

## Инструкция по запуску API

1. **Настройка окружения**
   ```
   cd rzproger_py
   composer install
   ```

2. **Запуск сервера для доступа с Android-устройств**
   ```
   php artisan serve --host=0.0.0.0 --port=8000
   ```

3. **URL для доступа с Android-устройств**
   - Для эмулятора: `http://10.0.2.2:8000/api/`
   - Для реального устройства: `http://IP-ВАШЕГО-КОМПЬЮТЕРА:8000/api/`

## Доступные API-эндпоинты

### Публичные эндпоинты

1. **Аутентификация**
   - `POST /api/login` - вход в систему
   - `POST /api/register` - регистрация нового пользователя

2. **Комнаты**
   - `GET /api/rooms` - список всех типов комнат
   - `GET /api/rooms/{id}` - детали конкретного типа комнаты
   - `POST /api/rooms/search` - поиск доступных комнат

3. **Дополнительные услуги**
   - `GET /api/extra-services` - список всех дополнительных услуг

### Защищенные эндпоинты (требуется токен авторизации)

1. **Профиль**
   - `GET /api/user` - информация о текущем пользователе
   - `POST /api/logout` - выход из системы (удаление токена)

2. **Бронирования**
   - `GET /api/bookings` - список бронирований пользователя
   - `POST /api/bookings/create` - создание бронирования
   - `GET /api/bookings/{id}` - детали бронирования
   - `POST /api/bookings/{id}/cancel` - отмена бронирования

## Форматы данных

### Тип комнаты (RoomType)
```
{
  "id": 1,
  "name": "Стандартный номер",
  "description": "Удобный номер для одного или двух гостей",
  "price_per_night": 5000,
  "max_occupancy": 2,
  "image": "/storage/room-types/standard.jpg",
  "amenities": ["Wi-Fi", "Кондиционер", "Телевизор"]
}
```

### Комната (Room)
```
{
  "id": 1,
  "room_number": "101",
  "room_type_id": 1,
  "is_available": true,
  "notes": null,
  "room_type": {
    "id": 1,
    "name": "Стандартный номер",
    "price_per_night": 5000,
    "max_occupancy": 2
  }
}
```

### Бронирование (Booking)
```
{
  "id": 1,
  "user_id": 5,
  "room_id": 1,
  "check_in_date": "2023-05-15",
  "check_out_date": "2023-05-20",
  "guests": 2,
  "total_price": 25000,
  "status": "pending",
  "special_requests": "Номер на верхнем этаже",
  "created_at": "2023-05-01T12:00:00.000000Z",
  "room": {
    "id": 1,
    "room_number": "101",
    "room_type": {
      "id": 1,
      "name": "Стандартный номер"
    }
  },
  "extra_services": [
    {
      "id": 1,
      "name": "Завтрак",
      "price": 1000,
      "pivot": {
        "quantity": 2,
        "price": 1000
      }
    }
  ]
}
```

### Дополнительная услуга (ExtraService)
```
{
  "id": 1,
  "name": "Завтрак",
  "description": "Континентальный завтрак",
  "price": 1000
}
```

## Аутентификация в Android-приложении

1. **Вход в систему**
   - Отправить POST-запрос на `/api/login` с email и password
   - Получить токен в формате: `{"token": "YOUR_TOKEN"}`
   - Сохранить токен в SharedPreferences

2. **Использование токена**
   - Добавлять заголовок к каждому запросу: 
     `Authorization: Bearer YOUR_TOKEN`

## Настройка Retrofit в Android-приложении

```kotlin
// RetrofitClient.kt
import okhttp3.OkHttpClient
import okhttp3.Request
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object RetrofitClient {
    private const val BASE_URL = "http://10.0.2.2:8000/api/" // Для эмулятора
    // private const val BASE_URL = "http://YOUR_IP:8000/api/" // Для реального устройства
    
    private val okHttpClient = OkHttpClient.Builder()
        .addInterceptor { chain ->
            val original = chain.request()
            
            // Получаем сохраненный токен
            val token = SessionManager.getToken()
            
            val requestBuilder = original.newBuilder()
                .header("Accept", "application/json")
            
            // Если есть токен, добавляем его в заголовок
            if (!token.isNullOrEmpty()) {
                requestBuilder.header("Authorization", "Bearer $token")
            }
            
            val request = requestBuilder.build()
            chain.proceed(request)
        }
        .build()

    val retrofit: Retrofit = Retrofit.Builder()
        .baseUrl(BASE_URL)
        .addConverterFactory(GsonConverterFactory.create())
        .client(okHttpClient)
        .build()
}
```

## Запуск проекта

1. Для запуска API выполните:
   ```
   cd rzproger_py
   php artisan serve --host=0.0.0.0 --port=8000
   ```

2. Проверьте доступность API по адресу:
   ```
   http://localhost:8000/api/rooms
   ```

3. В Android-приложении укажите правильный адрес сервера:
   ```kotlin
   // Для эмулятора
   private const val BASE_URL = "http://10.0.2.2:8000/api/"
   // Для реального устройства
   private const val BASE_URL = "http://YOUR_IP:8000/api/"
   ``` 