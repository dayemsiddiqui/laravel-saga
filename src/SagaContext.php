<?php

namespace dayemsiddiqui\Saga;

use dayemsiddiqui\Saga\Models\SagaRun;

class SagaContext
{
    protected array $cached;

    public function __construct(protected SagaRun $saga)
    {
        $this->cached = $saga->context ?? [];
    }

    public function get(string $key, $default = null): mixed
    {
        return $this->cached[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->cached[$key] = $value;
        $this->persist();
    }

    public function merge(array $data): void
    {
        $this->cached = array_merge($this->cached, $data);
        $this->persist();
    }

    public function forget(string $key): void
    {
        unset($this->cached[$key]);
        $this->persist();
    }

    public function persist(): void
    {
        $this->saga->update(['context' => $this->cached]);
    }

    public function all(): array
    {
        return $this->cached;
    }
}
