# Eloquent

Eloquent models should be defined in this order:
- traits
- constants
- primary key
- table
- timestamps (optional - only for models without timestamps)
- fillable attributes
- casts
- getters/setters
- relationships
- factory/builder methods

Every intermediate table of many-to-many relationship should
have its own pivot eloquent model located in `app/Models/Pivot`.

## Factories

Each eloquent model should have factory class defined **except intermediate eloquent classes** (pivot tables).

When defining a factory, define every possible state method.

When state method does not have an arguments, the name should be an **adjective** (i.e. `used()`, `emailVerified()`, `deactivataed()`).

When state method has an argument, and it changes some model attributes, the name should start with `of` (i.e. `ofEntity()`, `ofEmail()`).

When state method has an argument, and it defines some relationship state, the name should start with `with` (i.e. `withUser()`, `withEntity()`).
