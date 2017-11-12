# Minimal PHP framwork
[![Build Status](https://travis-ci.org/GaetanRoger/minimal-php-framework.svg?branch=master)](https://travis-ci.org/GaetanRoger/minimal-php-framework)
[![Coverage Status](https://coveralls.io/repos/github/GaetanRoger/minimal-php-framework/badge.svg?branch=master)](https://coveralls.io/github/GaetanRoger/minimal-php-framework?branch=master)


## Concept
### General idea
This minimal framework is meant to be used for a Model & Manager PHP based application.

- Model: class that represents one key data object of your application
- Manager: singleton class managing all instances of one model

If your application is about cars, you could have the following classes and methods:
```
- Car
  - drive()
  - stop()
  - getDriver()
- CarManager
  - createNewCar()
  - getCarWithId(int)
```

### With the framework
This minimal framework defines two classes for you: `AbstractModel` and `AbstractManager`.
Both predefine methods that every model and manager need, like `Manager.find(int)`, 
`Manager.findAll()`, `Model.getId()`, `Model.insert()`, etc. (please find all defined methods in [Methods list](#methods-list) down below).

This framework makes data persistence easy by using properties and class names to match database table names.
For example, if a model is named `AwesomeCar` the framework will look for a table named `awesome_car`. 



## How to install
This project is available as a [Composer dependency](https://packagist.org/packages/gaetanroger/minimal-php-framework).
Please use the following command:
```
composer require gaetanroger/minimal-php-framework
```

## How to use
At the beginning of your application, please set the PDO connection in the model and manager like this:
```php
AbstractModel::$database = $yourPDOConnection;
AbstractManager::$database = $yourPDOConnection;
```
This will allow the model to update and insert itself into the database, and the manager to find models (from an ID for 
example). Plus, all your models and manager will be able to easily access database.

Then simply make all your models and managers inherit from `Gaetanroger\MinimalPhpFramework\AbstractModel` and 
`Gaetanroger\MinimalPhpFramework\AbstractManager.` Be careful to follow naming conventions specified down below, as names are by default
dynamically used by the framework.

## Conventions to follow
##### Model
* A model class name **must** use CamelCase;
* A model class name **should not** end with `Model` (except if it is part of the actual model name);
* The model class name **must** be the exact conversion from snake_case to CamelCase of the table name.

Valid model names with their table name:
```
FooBar --> foo_bar
Person --> person
AwesomeMechanicalPiece --> awesome_mechanical_piece
```
Invalid model names:
```
Foo_bar
person
AweSomeMechanicalPiece
```

##### Manager
* A manager class name **must** use CamelCase;
* A manager class name **must** end with `Manager`;
* A manager class name **must** not contain `Manager` anywhere else;
* The manager class name **must** be identical with the model name plus the word `Manager`. 

Valid manager names with their model name:
```
FooBarManager --> FooBar
PersonManager --> Person
AwesomeMechanicalPieceManager --> AwesomeMechanicalPiece
```
Invalid manager names:
```
FooBar
FooBarmanager
FooBar_Manager
FooManagerBarManager
```

## <a name="methods-list">Properties and methods list</a>
### AbstractModel
- **$id**: ID of the model. The database field must be set to auto increment, as the framework uses this value to check
if the model is already inserted into database or not.
- **insert()**: Create a now row in the table named after the model class by using the model properties as field names.
- **update()**: Same as `insert()` but updates the fields values of the row matching the model ID.
- **delete()**: Delete the table row matching the model ID.
- **getId()**: Returns model's ID.
- **getTableName()**: Returns the model table name by converting the class name to snake_case. This method can be
overridden if a custom table name must be used.

### AbstractManager
- **find(id)**: Returns the model matching the ID from database. If no model matches, an exception is thrown.
- **findWhere(conditions)**: Returns the model filling the conditions. All the conditions are strict equality 
checks (`=` sign). If a different condition check is necessary, feel free to implement a new method.
- **findAll()**: Returns all models managed by this manager.
- **getTableName()**: Returns the model table name by converting the class name (without the word `Manager`) to 
snake_case. This method can be overridden if a custom table name must be used.
- **getModelName(short)**: Returns the model name by trimming the word `Manager` from the class name. If `short` is 
false, will return full class name (package/model). This method can be overridden if a custom manager name must be used.
- **count(column, distinct)**: Counts rows in table using SQL `COUNT`. Default parameters will simply count all rows but
you can specify what column you want to count and if you want it to only count distinct values.

## Changelog
Please take a look at [CHANGELOG.MD](CHANGELOG.MD)

## Please criticize
Please feel free to contact me here or by [email](mailto:gaetanroger.fr@gmail.com) if something bothers you or does not work as expected.