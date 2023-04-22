# TableService flow

- ConsoleCommand -> OrderManager/Command/Start
- OrderManager/StartHandler (add SagaId) -> OrderManager/Event/TableServiceStarted
- OrderManager/TableServiceStartedHandler