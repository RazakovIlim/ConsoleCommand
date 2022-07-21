<?php

interface ConsoleCommand {

  /** Получить Описание команды, аргументы и опции которые она принимает */
  public function getDescriptionCommands(): string;

  /** Получить опции по умолчанию */
  public function getPossibleOptions(): array;

  /** Получить аргументы по умолчанию */
  public function getPossibleArguments(): array;

  /** Получить ошибки, если они возникли */
  public function getErrors(): array;

  /** Запускает парсинг аргументов и опции */
  public function parseCommand(): bool;

  /** Парсит опции */
  public function parseOptions(): bool;

  /** Парсит аргументы */
  public function parseArguments(): bool;

  /** Собирает ошибки */
  public function setErrors(array $error): bool;

  /** Собирает аргументы */
  public function setArguments(string $argument): bool;

  /** Собирает опции */
  public function setOptions(array $option): bool;

  /** Результат, возвращает пустую строку или ошибку с опсианием */
  public function result(): string;
}