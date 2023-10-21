# My Virtual Pizza House
A space to explore modular asynchrony in PHP   
...on the example of a pizza house.

## Introduction

We have a reactive system here. 
This is a message-driven, service-oriented architecture with asynchronous flow orchestration.

The heart of the system is the "Process Manager" module, which outsources tasks to other modules/services.

Each module runs as a separate process (messenger:consume) in a separate container. Thanks to this, the whole thing is very well scalable.
_(The only problem "for now" is the scalability of the "Process Manager" module. I'm working on solving it.)_

I used the "Process Manager"/"Saga" pattern here at the level of the entire process/processes. 
Each message that goes to the Process Manager triggers a decision-making process (in the handler). 
The handler has access to the process state (entity/aggregate). 
The decision is therefore made based on data from the message and from the current "process state".
Thanks to this approach, we can handle very long-term processes.
We can integrate our system with remote systems and wait a long time for information from it.

There is nothing stopping you from expanding the process elements to include compensation. 
Then the system will become fully compliant with the "Saga" pattern.

## Kickstart
```shell
make up
make init
make console

```

## Start the machinery
### Option 1
```shell
# 1th console (watch the logs)
make dev-consume

# 2nd console (can be several times)
make dev-go
```

### Option 2
```shell
# inside 1th docker console
./bin/console messenger:consume process_manager_transport menu_transport waiter_transport kitchen_transport

# inside 2nd docker console
./bin/console app:serving-customers:start TBL1
```
Nothing spectacular, but... it works!

### Option 3
```shell
# inside 1th docker console
./bin/console messenger:consume process_manager_transport
# inside 2nd docker console
./bin/console messenger:consume menu_transport
# inside 3rd docker console
./bin/console messenger:consume waiter_transport
# inside 4th docker console
./bin/console messenger:consume kitchen_transport

# inside 5nd docker console
./bin/console app:waiter:start TableId
```

You can watch RabbitMQ queues: http://localhost:1567 (user/user)

## Tests
Inside console
```shell
make tests-unit
make tests-coverage
make tests-mutation # see var/infection-logs.html
```
...I know there's still a lot to do here ;)

## Architecture
**Service Oriented Architecture with Asynchronous Flow Orchestration**

```txt
                           Message Bus
                                |
[Order Manager] --(commands)--> |
                <--(events)---- |
                                | --(commands)--> [Waiter]
                                | <--(events)----
                                |
                                | --(commands)--> [Menu]
                                | <--(events)----
                                |
                                | --(commands)--> [Kitchen]
                                | <--(events)----
```
Communication is only between the orchestrator and the module.
Never between module and module directly.

Commands and events should be symmetrical:
- command: 'DoSomething'
- event: 'SomethingDone'


## Scenario
In this example we have a scenario: `TableService`.
Scenario directory is: `App/ProcessManager/Application/MessageHandler/TableService`.
The scenario is a simplified action of ordering pizza and fulfilling this order.

- UI = User Interface (terminal, controller, handler...)
- OM = Order Manager
- M = Menu
- W = Waiter
- K = Kitchen


UI: command `Start` -> msgBus 
- -> OM:`StartHandler` -> `TableServiceStarted` -> msgBus
- -> OM:`TableServieStartedHandler` -> `GetMenu` -> msgBus
- -> M:`GetMenuHandler` -> `MenuGot` -> msgBus
- -> OM: `MenuGotHandler` -> (`ShowMenu` -> msgBus or `PlaceOrder` -> msgBus)
- -> W: `ShowMenuHandler` -> `MenuShown` -> msgBus
- -> OM: `MenuShownHandler` -> `PlaceOrder` -> msgBus
- -> W: `PlaceOrderHandler` -> `OrderPlaced` -> msgBus
- -> OM: `OrderPlacedHandler` -> `DoPizza` -> msgBus (many commands, depending on order)
- -> K: `DoPizzaHandler` -> `PizzaDone` -> msgBus
- -> OM: `PizzaDoneHandler` -> (if all Pizzas Done) -> `ServePizzas`
- -> W: `ServePizzasHandler` -> `PizzasServed` -> msgBus
- -> OM: `PizzasServedHandler` -> `ShowBill` -> msgBus
- -> W: `ShowBillHandler` -> `BillPaid` -> msgBus
- -> OM: `BillPaidHandler` -> `ThankClient` -> msgBus
- -> W: `TankClientHandler`

## Tools
Inside console
#### Rector instantly upgrades and refactors the PHP code of your application
https://github.com/rectorphp/rector
```shell
make rector
```

## Heavy tasks
```shell
# inside 1th docker console
./bin/console messenger:consume heavy_worker_transport
# inside 2nd docker console

# find consumer PID
ps aux |grep messenger:consume
# start heavy command
./bin/console app:heavy-worker:start
# kill consumer
kill consumer_pid
```

