# DR-02: Adopción de Múltiples Estrategias de Validación e Inicialización de Atributos para Versiones de API Distintas

-   **Status:** Proposed
-   **Date:** 2025-07-24
-   **Authors:** Israel David Villarroel Moreno

---

## Context and Problem Statement

El proyecto actual busca ofrecer una API con versionado, donde las diferentes versiones podrían beneficiarse o requerir distintos enfoques para la validación e inicialización de atributos de los modelos.

Existe un debate sobre la mejor práctica para manejar la validación e inicialización de atributos:
1.  **Validación e Inicialización basada en Traits en el Modelo:** Proporciona un control más granular y es flexible para contextos no HTTP (Livewire, Filament, tareas de consola).
2.  **Validación con `Form Request`:** Es la forma canónica de Laravel para validaciones en el contexto HTTP y ofrece una integración fluida con los controladores.

El problema radica en cómo elegir y aplicar una estrategia de manera consistente a lo largo del ciclo de vida del proyecto, especialmente cuando se introducen nuevas versiones de la API que podrían explorar o adoptar paradigmas de desarrollo diferentes. La decisión debe permitir la exploración de ambos enfoques en diferentes contextos de la API, sin generar confusión a largo plazo.

---

## Considered Options

Se consideraron las siguientes opciones para la estrategia de validación e inicialización de atributos en un API versionada:

-   **Opción 1: Unificar un Único Enfoque para Todas las Versiones:** Seleccionar exclusivamente `Form Request` o `Traits` para todas las versiones futuras de la API.
    * **Breve descripción:** Toda la API, independientemente de su versión, se adheriría a un solo método para validación e inicialización.
-   **Opción 2: Adopción de Múltiples Estrategias por Versión (Opción Elegida):** Implementar la validación e inicialización basada en Traits para la API v1 y la validación basada en `Form Request` para la API v2.
    * **Breve descripción:** La API v1 utilizará el `AttributesTrait` para validación e inicialización, mientras que la API v2 utilizará `Form Request` para validación.
-   **Opción 3: Alternar Estrategias Sin Claridad de Versión:** Permitir que los desarrolladores elijan libremente entre `Form Request` o `Traits` en cualquier parte de cualquier versión de la API.
    * **Breve descripción:** Ausencia de una guía o estándar claro, dejando la decisión a nivel de implementación individual.

---

## Decision Outcome

**Chosen Option:** Opción 2: Adopción de Múltiples Estrategias por Versión.

### Rationale

La elección de adoptar múltiples estrategias de validación e inicialización, asignando el enfoque de **Traits a la API v1** y el enfoque de **Form Requests a la API v2**, se justifica por las siguientes razones:

1.  **Exploración y Comparación de Paradigmas:** Permite al equipo de desarrollo experimentar y comparar en un entorno real los beneficios y desventajas de ambos enfoques en diferentes contextos de API. Esto es crucial para futuras decisiones arquitectónicas y para comprender cuándo cada estrategia brilla más.
    * La **API v1 (Traits)** servirá como un caso de estudio para validar y medir la eficiencia del control granular de atributos y su flexibilidad fuera de los contextos HTTP tradicionales (ej., Livewire, Filament).
    * La **API v2 (Form Requests)** representará la aproximación estándar de Laravel, permitiendo evaluar su simplicidad y la madurez de su ecosistema para APIs puramente HTTP.

2.  **Preparación para Evolución Futura:** Al tener conocimiento práctico de ambos métodos, el equipo estará mejor posicionado para tomar decisiones informadas sobre la estrategia de validación para futuras versiones mayores de la API o para nuevos proyectos, basándose en la experiencia directa.

3.  **Modularidad de Modelos:** Para facilitar esta coexistencia y evitar la "contaminación" de lógica específica de validación, se han creado modelos separados (`UserWithTrait` y `User`). Esto asegura que cada enfoque tenga su propio modelo de datos con la lógica acoplada de manera apropiada (trait vs. ausencia de trait para usar Form Request).

4.  **Demostración de Flexibilidad:** El proyecto sirve como una plantilla que demuestra la capacidad de Laravel para manejar diversas arquitecturas de validación, ofreciendo opciones al desarrollador dependiendo de los estándares y requerimientos específicos de un proyecto.

Esta decisión fue seleccionada sobre las otras opciones porque:
* **A diferencia de la Opción 1 (Unificar un Único Enfoque):** No nos fuerza a una decisión prematura sobre la "mejor" estrategia sin haberlas evaluado a fondo en contextos diferentes del mismo sistema. Permite una comparación empírica.
* **A diferencia de la Opción 3 (Alternar Estrategias Sin Claridad):** Establece una directriz clara y explícita de *cuándo* usar cada enfoque (v1 vs. v2), previniendo la anarquía de estilos de validación que llevaría a inconsistencia y confusión.

### Consecuencias de la implementación:

Para manejar la separación de los dos enfoques, se han creado dos modelos distintos para el mismo concepto (ej., `UserWithTrait` para la API v1 y `User` para la API v2). Esto evita la "contaminación" del modelo principal con lógica que no le corresponde a su versión. Los controladores, tests y demás elementos asociados a cada versión de la API (`v1` y `v2`) estarán vinculados únicamente con su enfoque de validación/inicialización correspondiente.

---

## Consequences

### Positive Consequences

-   **Evaluación Práctica:** Permite una comparación directa y empírica de dos patrones de validación prominentes en un entorno de API real.
-   **Flexibilidad en Plantillas:** La plantilla resultante es más versátil, demostrando cómo adaptar la validación según las necesidades o estándares del proyecto.
-   **Código Claro por Versión:** La asignación de un enfoque a cada versión (v1 -> Traits, v2 -> Form Request) asegura que el código dentro de cada versión de la API sea consistente con su estrategia elegida.
-   **Modelos Desacoplados:** El uso de `UserWithTrait` y `User` evita que la lógica de un enfoque interfiera con el otro, manteniendo la claridad en el diseño de los modelos.

### Negative Consequences

-   **Complejidad de Mantenimiento a Corto Plazo:** Para esta plantilla en particular, mantener dos conjuntos de lógica de validación e inicialización para el mismo concepto (usuario) en diferentes versiones introduce una complejidad inicial.
-   **Advertencia de Uso en Proyectos Reales:** Se requiere una advertencia explícita en la plantilla para indicar que en un proyecto de producción real, se debe seleccionar un *único* enfoque para evitar confusiones y duplicación de mantenimiento a largo plazo.
-   **Duplicación de Conceptos de Modelo:** Aunque justificado para la demostración, tener `UserWithTrait` y `User` para el mismo concepto en una base de datos podría parecer redundante para un observador externo si no se comprende el contexto de esta plantilla.

### Neutral Consequences

-   **Requiere Decisión Futura:** La decisión final sobre cuál enfoque usar en proyectos futuros seguirá siendo necesaria, aunque ahora estará basada en experiencia práctica.
-   **Impacto en Herramientas:** Las herramientas de generación de documentación (como OpenAPI) y pruebas deberán configurarse para manejar ambos enfoques y sus respectivos modelos/controladores.