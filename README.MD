# Minimal PHP framwork
[![Build Status](https://travis-ci.org/GaetanRoger/minimal-php-framework.svg?branch=master)](https://travis-ci.org/GaetanRoger/minimal-php-framework)
[![Coverage Status](https://coveralls.io/repos/github/GaetanRoger/minimal-php-framework/badge.svg?branch=master)](https://coveralls.io/github/GaetanRoger/minimal-php-framework?branch=master)

This minimal framework is meant to be used for Model & Manager based application.

## Install
This project is available as a [Composer dependency](https://packagist.org/packages/gaetanroger/minimal-php-framework).
Please use the following command:
```
composer require gaetanroger/minimal-php-framework
```

## Conventions to follow
##### Model
* A model class name **must** use CamelCase;
* A model class name **must not** end with `Model` (except if it is part of the actual model name);
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

## Changelog
Please take a look at [CHANGELOG.MD](CHANGELOG.md)