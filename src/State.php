<?php

namespace NerdGeneration\Macro;

/**
 * State
 *
 * Contains variables and function specific defines for a specific runtime environment, and can be
 * kept and re-used between environments if necessary. Basically, global variables.
 *
 * @author Mark Griffin
 */
class State {
    /** @var array Associative array of state data */
    protected array $data = [];

    /**
     * Gets a state value
     *
     * @param string $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->data[$name] ?? $default;
    }

    /**
     * Gets all state values
     *
     * @return array
     */
    public function bulkGet(): array
    {
        return $this->data;
    }

    /**
     * Sets a state value
     *
     * @param string $name
     * @param mixed $value
     * @return State
     */
    public function set(string $name, mixed $value): static
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * Sets a batch of state values
     *
     * @param array $values
     * @return State
     */
    public function bulkSet(array $values): static
    {
        $this->data = array_merge($this->data, $values);
        return $this;
    }

    /**
     * Copies all values from another state
     *
     * @param State $state
     * @return State
     */
    public function copyFrom(State $state): static
    {
        return $this->bulkSet($state->bulkGet());
    }
}
