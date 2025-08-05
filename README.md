# External Order API Integration (Laravel + DDD + Event Sourcing)

## Overview

This project integrates with an **External Order API** that exists in **two different versions**:

- **V1 (REST)**: A traditional JSON-based structure.
- **V2 (JSON:API)**: Follows the JSON:API specification.

Both versions serve the same business purpose (handling orders) but:
- Accept **different request formats**.
- Return **different response formats**.

We handle this with a **serializer-based strategy** so that the **domain layer** never depends on API format details.

---

## Goals

- **Clean separation of concerns**:
    - Domain layer works only with **value objects, aggregates, and DTOs**.
    - Infrastructure layer handles **API-specific transformations and communication**.
- **Runtime version switching**: Choose V1 or V2 at runtime.
- **Maintainability**: Adding a new API version requires minimal changes.
- **Testability**: Serializers and mappers can be tested independently.

---

## Folder Structure

```
app
├── Application
│   ├── Contracts
│   │   └── ExternalOrderApiInterface.php  # Common API contract
│   ├── Http
│   │   └── Controllers
│   │       └── OrderController.php        # Entry point for order actions
│   └── VersionResolver.php                 # Decides which API version to use
│
├── Domain
│   ├── Order
│   │   ├── AggregateRoots
│   │   ├── ValueObjects
│   │   ├── Enums
│   │   ├── Events
│   │   ├── Casts
│   │   └── Order.php
│   └── Customer
│       └── Customer.php
│
└── Infrastructure
    ├── DTOs
    │   └── InternalOrderData.php           # Internal representation
    ├── ExternalOrderApi
    │   ├── DTOs                            # V1/V2 request & response DTOs
    │   ├── ExternalApiClient.php           # Handles HTTP calls
    │   └── ExternalOrderApiFactory.php     # Creates correct serializer/client
    ├── Mappers
    │   └── OrderApiDataMapper.php          # Maps between domain and API DTOs
    └── Providers
```

---

## Data Flow

1. **Incoming Request**  
   `OrderController` receives input from an HTTP request.

2. **Version Resolution**  
   `VersionResolver` decides whether to use API **V1** or **V2**.

3. **Factory Selection**  
   `ExternalOrderApiFactory` returns an instance of `ExternalOrderApiInterface` implementation for the chosen version.

4. **Serialization**  
   The selected API service uses a serializer/mapper to:
    - Transform **`InternalOrderData`** → **External API DTO** for requests.
    - Transform **External API Response DTO** → **`ExternalOrderV1ResponseData`** for domain use.

5. **HTTP Communication**  
   `ExternalApiClient` sends the serialized data to the external API and returns a ready to use object.

6. **Deserialization**  
   The API service converts the raw JSON into a **version-agnostic domain DTO**.

7. **Domain Processing**  
   The domain layer (aggregates, value objects) processes the data without knowing the API format.

---

## Example Workflow

```php
// Step 1: Determine which API version to use at runtime (V1 or V2)
$version = $this->versionResolver->resolve();

// Step 2: Convert internal order data into the correct API request format
//         The mapper transforms based on the selected version
$mapper = OrderApiDataMapper::map($data, $version);

// Step 3: Resolve the correct API client for the version and make the API call
//         This returns an external orders DTO (version-agnostic at this point)
$externalOrders = ExternalOrderApiFactory::resolve($version)->call($mapper);

// Step 4: Use the domain aggregate root to persist order data internally
//         This step applies domain rules and records domain events
OrderAggregateRoot::retrieve($externalOrders->id)
    ->create(
        customer: $data->customer,       
        total: $data->total,                  
        items: $data->items->toArray()     
    );

// Step 5: Return the result from the external API
//         This is usually returned to the controller or service caller
return $externalOrders;
```

---


# Notes on Current State & Missing Pieces

## Known Limitations

1. **OrderProjector**
    - This class is unfinished, some date isn't being stored.

2. **No Automated Tests Yet**
    - Due to time, **unit tests and integration tests** are missing.
    - Critical areas to test in the future:
        - **Mappers**: Ensure correct data transformation for each API version.
        - **External API mock**: Simulate responses for both V1 and V2.
        - **Domain calculations**: Verify total amount calculations match business rules.

3. **API Mocking & Verification of Totals**
    - For stable local development, the external API should be **mocked**.
    - Mocking allows:
        - Fast test execution.
        - Verifying total calculations without calling the real API.
        - Running the app offline.

4. **Always Return JSON Responses**
    - Currently missing a **global handler** or **middleware** that forces JSON responses.
    - Without this, Laravel might return HTML for some exceptions.
    - Add a change in `Handler.php` or global middleware to always return JSON.

5. **Version Switching via Header**
    - API version is chosen at runtime by sending the header:
      ```
      X-Order-Version: 1   // for V1 (REST)
      X-Order-Version: 2   // for V2 (JSON:API)
      ```
    - `VersionResolver` reads this header and decides which API client to use.

6. Project uses DOCKER with embedded php server (dev only). The Docker image user supervisord, so we can centralize the processes.
---

## Completed for Project Start

- **Makefile** (or `make up`) command is ready to bootstrap the environment.
- **PHP-CS-Fixer** integrated for code style consistency:
  ```bash
  composer php-cs-fixer
  ```
- setup env, example:
```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=./database.sqlite
DB_FOREIGN_KEYS=true

QUEUE_CONNECTION=database

EXTERNAL_ORDERS_API_V1_URI=https://dev.micros.services/api/v1/order
EXTERNAL_ORDERS_API_V2_URI=https://dev.micros.services/api/v2/order
```

- Make a POST Request to: http://localhost:8000/api/orders
  With body:
```json
{
    "customer": {"full_name": "João Almeida", "nif": "504321331"},
    "total": {"amount": "100.0", "currency": "EUR"},
    "items": [
        { "sku": "PEN-16GB", "qty": 3, "unit_price": "9.90" },
        { "sku": "NOTE-A5", "qty": 10, "unit_price": "12.00" }
    ]
}
```
- Header: 'X-Order-Version' default is to v1.
- Voila, the data is magically serialized to the external services.
---

## Design Choices

### Laravel Data Instead of JSON Resources
- **Reason**:
    - Laravel's `ApiResources` is tied to HTTP responses, which is **application-layer specific**.
    - In DDD, we want **data transformations to exist independently** of the HTTP layer.
    - [Laravel Data](https://github.com/spatie/laravel-data) allows us to:
        - Define **immutable DTOs**.
        - Use them in **both incoming (request) and outgoing (response)** transformations.
        - Keep mappings reusable between the application and infrastructure layers.
    - This ensures that mappers work the same way **whether called from a controller or a CLI command**.

---

## Ubiquitous Language

The term **`ExternalOrderApi`** references the externals apis from the **requirements document** and used consistently across.

This matches **DDD's Ubiquitous Language principle**, keeping terminology aligned with the business and avoiding ambiguous names.

---

## Next Steps

1. Finish Projector and the flow of the event-sourcing.
2. Add:
    - API mocks for both V1 and V2.
    - Unit tests for mappers and version resolution.
    - Calculation verification tests for totals.
3. Implement a **global JSON response handler**.
4. OpenAPI integration.
5. Add a load balancing for balance the requests (maybe by health and weights).
6. Use NGINX as reverse proxy or traefik
7. Rate limiting.
8. Add ci/cd integration.
9. Cleanup some files (frontend, etc...)


