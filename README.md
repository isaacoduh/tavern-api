# Tavern API

## Tools and Technology

-   Laravel
-   PHP
-   MySQL
-   Twilio
-   Stripe

### Endpoints

| Endpoint                                           | Method | Description                           |
| -------------------------------------------------- | ------ | ------------------------------------- |
| `/api/v1/utils/get-countries-list`                 | GET    | Retrieves a list of countries.        |
| `/api/v1/utils/get-states-by-country/{country_id}` | GET    | Retrieves states based on country ID. |
| `/api/v1/utils/get-cities-by-state/{state_id}`     | GET    | Retrieves cities based on state ID.   |
| `/api/v1/outlet`                                   | POST   | Creates a new outlet for a seller.    |
