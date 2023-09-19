# TableService flow

- ConsoleCommand -> ProcessManager/Command/Start
- ProcessManager/StartHandler (add SagaId) -> ProcessManager/Event/TableServiceStarted
- ProcessManager/TableServiceStartedHandler
