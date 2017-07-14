# Base PHP framwork

This framework is meant to be used for Model & Manager based application.

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
### v1.0.0
* Adds AbstractModel
* Adds AbstractManager

#### v1.0.1
* Remove unused Slim dependency

#### v1.0.2
* Fix README.MD typo
* Remove Slim in package description

#### v1.0.3
* Adds changelog into README.MD
* Fix: forgot to bumb version number

#### v1.0.4
* Fix README.MD typo
