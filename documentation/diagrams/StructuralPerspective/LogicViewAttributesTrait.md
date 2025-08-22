
``` mermaid
classDiagram
    direction LR

    class AttributesTrait {
        <<trait>>
        +generateValidator(array arrayAttributes): Validator
        +initAttributes(array arrayAttributes): void
    }

    class ConcreteModelA {
        // Specific properties for Model A
        -id: int
        -name: string
        -email: string
        // ...
        +generateValidator(array arrayAttributes): Validator
        +initAttributes(array arrayAttributes): void
    }

    class Validator {

    }

    AttributesTrait <|..  ConcreteModelA : uses

    AttributesTrait --> Validator : returns
    ConcreteModelA ..> Validator : uses (for validation result)

```