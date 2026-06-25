<?php

namespace Src\Validator;

class Validator {
    private array $validators = [];
    private array $fields = [];
    private array $rules = [];
    private array $messages = [];
    private array $errors = [];

    public function __construct(array $fields, array $rules, array $messages = []) {
        $this->validators = app()->settings->app['validators'];
        $this->fields = $fields;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->validate();
    }

    private function validate(): void {
        foreach ($this->rules as $field => $validators) {
            $this->validateField($field, $validators);
        }
    }

    private function validateField(string $fieldName, array $validators): void {
        foreach ($validators as $validator) {

            $temp = explode(':', $validator);
            $validatorName = $temp[0];
            $args = isset($temp[1]) ? explode(',', $temp[1]) : [];

            $validatorClass = $this->validators[$validatorName] ?? null;

            if (is_null($validatorClass)) {
                throw new \Exception("Validator '$validatorName' does not exist.");
            }
            if (!class_exists($validatorClass)) {
                throw new \Exception("Cant find class for validator '$validatorName'.");
            }

            $validator = new $validatorClass($this->fields[$fieldName], $args);

            if (!$validator->rule()) {
                $this->errors[$fieldName][$validatorName] = $this->messages[$fieldName][$validatorName];
            }

        }
    }

    public function errors(): array {
        return $this->errors;
    }

    public function success(): bool {
        return empty($this->errors);
    }

}