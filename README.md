# TArray
This class does array processing and then method chaining allows for smart coding like in Kotlin and similar languages.

### Sample

```php
$array = new TArray();
$array->set("name", "john");

// return "john"
$name = $array->find("name");
```

```php
$array = new TArray();
$array->push(1);
$array->push(2);
$array->push(3);

// return 1
$number = $array->find(0);
```

```php
$array = new TArray([1, 2, 3]);

// return [3, 2, 1]
$result = $array->valueDescend()->all();

// return [1, 4, 9]
$result = $array->map(fn (int $_, int $value) => $value*2)->all();
```