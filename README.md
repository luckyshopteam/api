LuckyShop API
-------------

Данные классы предназначены для облегчения работы вебмастеров с API сайта luckyshop.ru

Отправка лида
-------------

Для отправки лида используйте класс `Request`


```
$request = new Request([
    'apiKey' => '5b9f76c0c3b5939189879658115',
 ]);
 
 $request->sendLead($lead);
```

Переменная `$lead` представляет из себя массив с данными покупателя и потока ```` name ````, 
`phone`, `ip`, `userAgent` ,` campaignHash` 

Пример:

```
$lead = [
    'name' => 'Вася',
    'phone' => 89153332255,
    'ip' => '125.92.123.122',
    'userAgent' => $_SERVER['HTTP_USER_AGENT'],
    'campaignHash' => 'e1v26dd8-6l41-4058-85gc-d18cfdsd43e0d',
];
```

Валидация данных
----------------
Вы можете использовать как собственную валидацию данных, так и воспользоваться классом `Lead`

Пример 1:
```
$data = [
    'name' => 'Вася',
    'phone' => 89153332255,
    'ip' => '125.92.123.122',
    'userAgent' => $_SERVER['HTTP_USER_AGENT'],
    'campaignHash' => 'e1v26dd8-6l41-4058-85gc-d18cfdsd43e0d',
];

$lead = new Lead($data);

$lead->validate();

```
Метод `validate()` вернет `true` или `false`.

Пример 2:

```
if ($lead->validate()) {
    $request->sendLead($lead);
}
```

Забор статусов
--------------

```
$request->getStatuses($ids);
```

Переменная `$ids` может представлять из себя как строку с id лидов, разделенных через запятую, так и массив данных

Отправка utm меток и сабов
--------------------------

Пример 1:
```
$request->sendLead($lead, ['utm_source' => 'utm', 'subid1' => 'sub']);
```

Пример 2:
```
$request->requestParams = ['utm_source' => 'utm', 'subid1' => 'sub'];

$request->sendLead($lead);
```