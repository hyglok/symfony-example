## This project shows solution for the next task:
```
You need to develop a small API for the airline. The API responds to the following user command requests:
1. reserve a seat on the flight (booking);
2. cancel the reservation (unbooking);
3. buy a ticket;
4. refund a purchased ticket.

Seat is just a number from 1 to 150. You can buy a ticket both after booking and without it. The functionality of payment and refund is not necessary to implement, just change the state.

It should be considered that the API is subscribed to receive events via the HTTP protocol (callback notifications) to one of its addresses. For example, http://localhost/api/v1/callback/events.

Types of notifications:
1. The sale of tickets for the flight has been finished;
2. The flight is canceled.

Notification example:
{"data":{"flight_id":1,"triggered_at":1585012345,"event":"flight_ticket_sales_completed","secret_key":"a1b2c3d4e5f6a1b2c3d4e5f6"}}

When a flight is canceled, the user who have booked or purchased tickets for this flight are sent flight cancellation e-mails in the background (asynchronously).
In the case of a response to the event with an HTTP code other than 200, the event will be retransmitted after a certain delay.
```

## Usage
* Install docker
* Run ```docker-composer up -d``` in the root directory of project
* Use localhost:8080 as host
* Follow [Documentation](DOC.md) to make requests

## Known issues
* Write acceptance tests

### Prod environment
```
Rabbit uses for messages
Redis uses for cache
```