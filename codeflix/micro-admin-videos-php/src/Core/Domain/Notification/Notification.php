<?php

namespace Core\Domain\Notification;

class Notification
{
    private array $errors = [];

    /**
     * @param $error array['context' => string, 'message' => string]
     * @return void
     */
    public function addError(array $error): void
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function messages(string $context = null): string
    {
        $message = "";

        foreach ($this->errors as $error) {
            if ($context && $error['context'] !== $context) {
                continue;
            }

            $message .= "{$error['context']}: {$error['message']}\n";
        }

        return $message;
    }
}
