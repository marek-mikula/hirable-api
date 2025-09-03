# Resources

Resource classes should be used everytime we need to transform a model into the JSON schema for a response.

## Flavours

Resource classes can have multiple "flavours" in case we need different data for different use cases. But
we should not overuse these "flavours" to avoid chaos and to ensure uniformity when talking to frontend (mobile/web).

Usually the resource class should have these flavours:

- **Brief** - contains only the most important information (id, name, email, phone etc.)
- **Base** - contains only direct attributes of the model, no relationships
- **Detail** - contains all the attributes in **Base** + relationships (max. verbosity is **Base**) + counts.
- **List** - optimized for detailed list views, contains all the attributes in **Base** + custom

More resource classes should not be needed. If frontend needs to load some additional data to render a detailed view,
**detail endpoint** should be used to pull the data.

## Naming

Resource classes should be named accordingly:

| Model | Flavour | Resource class name |
|-------|---------|---------------------|
| User  | Brief   | UserBriefResource   |
| User  | Base    | UserResource        |
| User  | Detail  | UserDetailResource  |
| User  | List    | UserListResource    |