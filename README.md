<p align="center">
    <h1 align="center">РЕШЕНИЕ ЗАДАНИЯ 1</h1>
    <br>
    <h1 align="center">SQL-запрос</h1>
    <br>
    <p><code> 
SELECT 
  user_id AS ID,
​  CONCAT (first_name, " ", last_name) AS Name,
  min(author) AS Author,
​  GROUP_CONCAT(name SEPARATOR ", ") AS Books
FROM user
​ JOIN user_books ON user.id = user_books.user_id
​ JOIN book ON book.id = user_books.book_id
WHERE DATEDIFF(return_date, get_date) <= 14 AND TIMESTAMPDIFF(YEAR, birthday, NOW()) BETWEEN 7 AND 17
GROUP BY 1
HAVING COUNT(distinct author) = 1 AND COUNT(*) = 2
     </code></p>
    <br>
    <br>
    <br>
    <br>
</p>


<p align="center">
    <h1 align="center">РЕШЕНИЕ ЗАДАНИЯ 2</h1>
    <br>
    <h1 align="center">JSON API сервис для работы с курсами обмена валют для биткоина (BTC) </h1>
    <br>
    <h4 align="center">На языке php 8 с использованием фреймворка yii2 с помощью Docker</h4>
    <br>
    <h3 align="center">Инструкция по развёртыванию и тестированию</h3>
    <br>
    <br>
</p>

<p>
<br>
<h2>1. Клонирование репозитория</h2>
<br>
    <p> Скачиваем архив из данного репозитория и распаковываем или клонируем данный репозиторий на свой локальный компьютер, выполнив команду в терминале:
        <p><code> git clone </code></p>
        <p>Далее выполняем команду:</p>
        <p><code> composer update </code></p>
    </p>
<br>
<br>
<h2>2. Развёртывание</h2>
<br>
    <p> 2.1 Устанавливаем Docker на свой локальный копьютер, если он ранее не был установлен.</p>
    <p> 2.2 Настройки Docker можно найти на официальном сайте  https://www.docker.com/products/docker-desktop </p>
    <p> 2.3 В терминале на папке с склонированным репозиторием выполняем команду:
        <p><code> docker-compose up -d </code></p>       
        Эта команда скачает все необходимые зависимости, поднимет контейнер на порту 8000, и API будет готов к работе.
    </p>
    <h4> Теперь API доступен по адресу http://localhost:8000/.</h4>
<br>
<br>
<h2>3. Тестирование</h2>
<br>
    <p> Чтобы проверить, что CurrencyController доступен, вводим в строку браузера http://localhost:8000/index.php?r=currency/index</p>
    <p> Чтобы проверить, функция actionRates доступна, вводим в строку браузера http://localhost:8000/api?method=GET&r=currency/rates. На экране должно появиться {"status":"error","code":403,"message":"Invalid token"}</p>
    <br>
    <h6> Для тестирования сайта можно использовать любое специализированное приложение, удобное тестировщику, которое будет направлять запросы api и получать ответы в формате JSON (например, PHPUnit и/или Postman).</h6>
    <br>
    <p> 3.1 Для тестирования метода actionRates необходимо выполнить следующий HTTP-запрос:
            http://localhost:8000/api?method=GET&r=currency/rates 
            Заголовок запроса должен содержать "Authorization: Bearer YOUR_TOKEN_HERE"
            В таком виде функция должна вернуть следующий ответ в формате json:
            {"status":"error","code":403,"message":"Invalid token"}
            Если YOUR_TOKEN_HERE заменить на 64 единицы, функция должна вернуть ответ в формате json со статусом success, кодом 200 и тело запроса, содержащее текущие курсы всех валют к биткоину.
    </p>
    <br>
    <p> 3.2 Для тестирования функции currencyConvert необходимо выполнить следующий HTTP-запрос:
        <p><code> http://localhost:8000/api?method=GET&r=currency/rates&currency_from=USD&currency_to=BTC&value=1.00 </code></p>
        Проверку авторизации выполняем аналогично предыдущему пункту.
        Для проверки значений в запросе подставляем значения параметров currency_from, currency_to, value на перечисленные в таблице:
        <table>
            <tr> <th> currency_from = </th> <th> currency_to = </th> <th> value = </th> <th> Результат </th> </tr>
            <tr> <td> USD </td> <td> BTC </td> <td> 1.00 </td> <td> Курс перевода USD в BTC </td> </tr>
            <tr> <td> BTC </td> <td> USD </td> <td> 1000.00 </td> <td> Курс перевода BTC в USD </td> </tr>
            <tr> <td> BTC </td> <td> USD </td> <td> 1.00 </td> <td> Ошибка: Minimum exchange rate is ... BTC </td> </tr>
            <tr> <td> USD </td> <td> BTC </td> <td> 0.001 </td> <td> Ошибка: Minimum exchange rate is 0.01 USD </td> </tr>
            <tr> <td> UUU </td> <td> BTC </td> <td> 0.001 </td> <td> Ошибка: Invalid currencies </td> </tr>
            <tr> <td> USD </td> <td> USD </td> <td> 0.001 </td> <td> Ошибка: Invalid currencies </td> </tr>
            <tr> <td> BTC </td> <td> BTC </td> <td> 0.001 </td> <td> Ошибка: Invalid currencies </td> </tr>            
        </table>
        Напоминаю, что все функции должны возвращать ответы только в формате JSON.
    </p>
    <p>Для написания тестов с помощью PHPUnit необходимо будет создать отдельный тестовый класс и методы для проверки каждого метода API.</p>
    <br>
    <p>Пример тестового класса для проверки методов API вы можете найти в документации PHPUnit (https://phpunit.readthedocs.io/en/9.5/writing-tests-for-phpunit.html).</p>
    <br>
    <p>Для тестирования методов API с помощью Postman нужно будет создать коллекцию запросов, каждый из которых будет соответствовать одному методу API. В каждом запросе нужно будет указать параметры, отправляемые на сервер, и ожидаемый результат.</p>
    <br>
    <p>Пример коллекции запросов для тестирования API можно найти в документации Postman (https://learning.postman.com/docs/running-collections/intro-to-collection-runs/).</p>
    <br>
    <p>Также для автоматизации тестирования API можно использовать инструменты Continuous Integration, такие как Travis-CI или CircleCI. Эти инструменты позволяют автоматически запускать тесты после каждого коммита в репозиторий и оповещать разработчиков в случае возникновения ошибок.</p>
    <br>
    <h4>Важно убедиться, что тесты охватывают все возможные сценарии использования API и проверяют корректность работы каждого метода, а также учитывают все требования к API, указанные в задании. </h4>
    <br>
    <br>
    <br>
</p>
