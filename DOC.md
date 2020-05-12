
# Airlines Test Task



## Indices

* [Default](#default)

  * [Register Flight](#1-register-flight)
  * [Purchase Ticket](#2-purchase-ticket)
  * [Refund Ticket](#3-refund-ticket)
  * [Reserve seat](#4-reserve-seat)
  * [Cancel Reservation](#5-cancel-reservation)
  * [Pay Reservation](#6-pay-reservation)
  * [Flight Sales Completed](#7-flight-sales-completed)
  * [Flight Cancelled](#8-flight-cancelled)
  * [Flights](#9-flights)
  * [Reservations](#10-reservations)
  * [Tickets](#11-tickets)


--------


## Default



### 1. Register Flight


Some info fields ommited. This route just creates flight with id and state status.


***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://127.0.0.1:8000/api/v1/flights/register
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



### 2. Purchase Ticket



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://127.0.0.1:8000/api/v1/tickets/purchase
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
	"seat": 30,
	"customerId": "89824c42-ef0d-4238-be6e-d2c21bb63611",
	"flightId": "99824c42-ef0d-4238-be6e-d2c21bb63611",
	"firstName": "David",
	"lastName": "Jones",
	"email": "email@gmail.com"
}
```



### 3. Refund Ticket



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://127.0.0.1:8000/api/v1/tickets/refund/0824c29b-3de5-4127-abfe-9a0a687e8503
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



### 4. Reserve seat



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://127.0.0.1:8000/api/v1/reservations/reserve
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
	"seat": 32,
	"customerId": "89824c42-ef0d-4238-be6e-d2c21bb63611",
	"flightId": "99824c42-ef0d-4238-be6e-d2c21bb63611",
	"firstName": "David",
	"lastName": "Jones",
	"email": "email@gmail.com"
}
```



### 5. Cancel Reservation



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://127.0.0.1:8000/api/v1/reservations/cancel/80461282-13c0-4c32-a491-9122b7aeacbc
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



### 6. Pay Reservation



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://127.0.0.1:8000/api/v1/reservations/pay/f17cf8af-e52b-41dc-afcb-94267bac9f26
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



### 7. Flight Sales Completed



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://127.0.0.1:8000/api/v1/flights/events
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
	"secretKey": 123,
	"flightId": "99824c42-ef0d-4238-be6e-d2c21bb63611",
	"triggeredAt": "2020-03-10 17:16:18",
	"event": "ticket_sales_completed"
}
```



### 8. Flight Cancelled



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://127.0.0.1:8000/api/v1/flights/events
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
	"secretKey": 123,
	"flightId": "99824c42-ef0d-4238-be6e-d2c21bb63611",
	"triggeredAt": "2020-03-10 17:16:18",
	"event": "flight_cancelled"
}
```



### 9. Flights



***Endpoint:***

```bash
Method: GET
Type: RAW
URL: https://127.0.0.1:8000/api/v1/flights
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Responses:***


Status: Success | Code: 200



```js
{
    "data": [
        {
            "id": "3c2461b2-941d-4bd1-a6ed-87d171ca0f91",
            "status": "sale_opened"
        },
        {
            "id": "99824c42-ef0d-4238-be6e-d2c21bb63611",
            "status": "cancelled"
        }
    ]
}
```



### 10. Reservations



***Endpoint:***

```bash
Method: GET
Type: RAW
URL: https://127.0.0.1:8000/api/v1/reservations/99824c42-ef0d-4238-be6e-d2c21bb63611
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Responses:***


Status: Success | Code: 200



```js
{
    "data": [
        {
            "id": "3c2461b2-941d-4bd1-a6ed-87d171ca0f91",
            "status": "sale_opened"
        },
        {
            "id": "99824c42-ef0d-4238-be6e-d2c21bb63611",
            "status": "cancelled"
        }
    ]
}
```



Status: Success | Code: 200



```js
{
    "data": [
        {
            "id": "22cf32c3-fcb7-4baf-a63d-47742418bb86",
            "seat": 19,
            "status": "paid",
            "customerId": "89824c42-ef0d-4238-be6e-d2c21bb63611",
            "ticketId": null,
            "passengerFirstName": "David",
            "passengerLastName": "Jones",
            "passengerEmail": "hyglok@gmail.com"
        },
        {
            "id": "80461282-13c0-4c32-a491-9122b7aeacbc",
            "seat": 31,
            "status": "cancelled",
            "customerId": "89824c42-ef0d-4238-be6e-d2c21bb63611",
            "ticketId": null,
            "passengerFirstName": "David",
            "passengerLastName": "Jones",
            "passengerEmail": "hyglok@gmail.com"
        },
        {
            "id": "8a36533f-e154-48bf-819b-82e14a8a6351",
            "seat": 20,
            "status": "reserved",
            "customerId": "89824c42-ef0d-4238-be6e-d2c21bb63611",
            "ticketId": null,
            "passengerFirstName": "David",
            "passengerLastName": "Jones",
            "passengerEmail": "hyglok@gmail.com"
        }
    ]
}
```



### 11. Tickets



***Endpoint:***

```bash
Method: GET
Type: RAW
URL: https://127.0.0.1:8000/api/v1/tickets/99824c42-ef0d-4238-be6e-d2c21bb63611
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Responses:***


Status: Success | Code: 200



```js
{
    "data": [
        {
            "id": "7f2b43cf-015c-4e4e-b82f-9b58b128e291",
            "seat": 15,
            "status": "refunded",
            "customerId": "89824c42-ef0d-4238-be6e-d2c21bb63611",
            "passengerFirstName": "David",
            "passengerLastName": "Jones",
            "passengerEmail": "hyglok@gmail.com"
        },
        {
            "id": "0824c29b-3de5-4127-abfe-9a0a687e8503",
            "seat": 30,
            "status": "refunded",
            "customerId": "89824c42-ef0d-4238-be6e-d2c21bb63611",
            "passengerFirstName": "David",
            "passengerLastName": "Jones",
            "passengerEmail": "hyglok@gmail.com"
        },
        {
            "id": "e09d9f75-4213-451c-ad6c-8a5b508898db",
            "seat": 16,
            "status": "cancelled",
            "customerId": "89824c42-ef0d-4238-be6e-d2c21bb63611",
            "passengerFirstName": "David",
            "passengerLastName": "Jones",
            "passengerEmail": "hyglok@gmail.com"
        },
        {
            "id": "cdd75812-3d54-4db3-9377-b2301e225d69",
            "seat": 17,
            "status": "purchased",
            "customerId": "89824c42-ef0d-4238-be6e-d2c21bb63611",
            "passengerFirstName": "David",
            "passengerLastName": "Jones",
            "passengerEmail": "hyglok@gmail.com"
        }
    ]
}
```



---
[Back to top](#arlines-test-task)
> Made with &#9829; by [thedevsaddam](https://github.com/thedevsaddam) | Generated at: 2020-05-12 10:26:52 by [docgen](https://github.com/thedevsaddam/docgen)
