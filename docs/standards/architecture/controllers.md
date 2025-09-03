# Controllers

Each controller should have only these standard methods, which correspond to the standard REST endpoints:

| Controller method | Endpoint        | Comment                     |
|-------------------|-----------------|-----------------------------|
| index             | `GET: /`        | List of entities            |
| store             | `POST: /`       | Store a new entity          |
| show              | `GET: /{id}`    | Show a detail of the entity |
| update            | `PATCH: /{id}`  | Update the entity           |
| delete            | `DELETE: /{id}` | Delete the entity           |

If we have an uncommon controller with uncommon use-case (eg. `POST: /set-step`), then we
create a single-method invokable controller only for this specific user case (eg. `SetStepController`).