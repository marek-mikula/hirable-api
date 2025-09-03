# Eloquent

Eloquent models should be defined in this order:
- traits
- constants
- primary key
- table
- timestamps (optional - only for models without timestamps)
- touches (optional - only if model touches other model's timestamps)
- fillable attributes
- casts
- getters/setters
- relationships
- query builder factory method
- factory method
- other stuff

Every intermediate table of many-to-many relationship should
have its own pivot eloquent model located in `./Models/Pivot`.

## Factories

Each eloquent model should have factory class defined **except intermediate eloquent classes** (pivot tables).

When defining a factory, define every possible state method.

When state method does not have an arguments, the name should be an **adjective** (i.e. `used()`, `emailVerified()`, `deactivated()`).

When state method has an argument, and it changes some model attribute, the name should start with `of` (i.e. `ofEntity(Entity $entity)`, `ofEmail(string $email)`).

When state method has an argument, and it defines some relationship state, the name should start with `with` (i.e. `withUser(User $user)`, `withEntity(Entity $entity)`).
