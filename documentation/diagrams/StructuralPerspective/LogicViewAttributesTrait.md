
``` mermaid
classDiagram
    direction LR

    class AttributesTrait {
        <<trait>>
        +validateAttributes(array arrayAttributes): MessageBag | null
        +initAttributes(array arrayAttributes): void
    }

    class ConcreteModelA {
        // Specific properties for Model A
        -id: int
        -name: string
        -email: string
        // ...
        +validateAttributes(array arrayAttributes): MessageBag | null
        +initAttributes(array arrayAttributes): void
    }

    class MessageBag {
        // Represents validation error collection
        +add(key, message): void
        +get(key): array
        // ...
    }

    AttributesTrait <|..  ConcreteModelA : uses

    AttributesTrait --> MessageBag : returns
    ConcreteModelA ..> MessageBag : uses (for validation result)

```