# Domain Driven Design (DDD) project - Restaurant Business

## Introduction

Sample project created to practice and learn how to build a simple domain driven design (DDD) web application.

The following aspects of such an application are examined:

- Domain objects
- Layers (Application, Domain and Infrastructure)
- Unit testing
- MySQL Database infrastructure
- Doctrine infrastructure

Restaurant Business Modelling diagram:

[Modelling diagram](https://drive.google.com/file/d/1YCLQmQMe31okBSRxGhFvJZEZ0LvXtgft "Restaurant Business Modelling diagram")

Simplified Restaurant Business Class diagram:

[Simplified Class Diagram](https://drive.google.com/file/d/1XKvHCwYFbV-rwx69w_MeWZP-SiuRPlLH "Simplified Restaurant Business Class Diagram")

This is a simplified version of the class diagram because in a DDD project, the operations
are handled by the Infrastructure layer (see all interfaces and repositories in code).

## Prices

I used the [Brick\Money] (https://github.com/brick/money "Brick\Money money and currency PHP library") money and currency PHP library to deal with money.

## Dependencies

- "doctrine/orm" 
- symfony/dependency-injection"

## To be done:

1. Add specific Domain events
2. Domain Event listeners
3. Transactions

4. Factories (not needed for such a small project)
5. Domain Services and Infrastructure services (not needed for such a small project) 
6. Application Services (not needed for such a small project)  
7. Modules (not needed for such a small project)
8. Presentation layer
9. (eventually) external APIs

## Technologies to be explored in the context of the project:

Scrutinizer Code Quality, Travis CS & Static Analysis
