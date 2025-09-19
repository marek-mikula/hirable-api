# Repositories

Each model should have its own repository. All DB queries should be run trough this
repository if possible.

## Methods

Methods should have standard names.

- `store` - Stores a new model
- `index` - Lists all models, no pagination
- `update` - Updates existing model
- `delete` - Deletes existing model
- `find` - Finds single model by given ID
- `findBy` - Finds single model by given params
- `getBy` - Finds models by given params