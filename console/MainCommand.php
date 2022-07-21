<?php

class MainCommand implements ConsoleCommand {

  public string $currentPath = '';

  public function __construct(string $currentPath, array $commands) {
    $this->commands = $commands;
    $this->currentPath = $currentPath;
    $this->parseCommand();
  }

  public function getDescriptionCommands(): string {
    return "
    Команда main_command принимает 4 аргумента и 3 опции
    
    Аргументы:
      - verbose. Описание
      - overwrite. Описание
      - unlimited. Описание
      - log. Описание

    Опции:
      - log_file. Название лога
        - app.log
      - methods. Описание
        - create
        - update
        - delete
      - paginate. Описание
        - 50
    
    Пример вызова: app.php main_command {verbose,overwrite} [log_file=app.log] {unlimited} [methods={create,update,delete}] [paginate=50] {log}
    \n";
  }

  public function getPossibleArguments(): array
  {
    return ['verbose', 'overwrite', 'unlimited', 'log'];
  }

  public function getPossibleOptions(): array
  {
    return ['methods', 'paginate', 'log_file'];
  }

  public function getErrors(): array
  {
    $result = [];

    if (isset($this->errors)) {
      $result = $this->errors;
    }

    return  $result;
  }

  public function parseCommand(): bool
  {
    $this->parseArguments();
    $this->parseOptions();
    return empty($this->getErrors());
  }

  public function parseOptions(): bool
  {
    $requiredOptions = $this->getPossibleOptions();
    $notOptions = true;

    foreach ($this->commands as $command) {
      if (mb_substr_count($command, '[') > 0){

        $notOptions = false;
        $options = str_replace(['[', ']'], '', $command);
        if (mb_substr_count($options, '=') > 0) {
          $options = explode('=', $options);
          $optionName = $options[0];
          if (in_array($optionName, $requiredOptions)) {
            if (!empty($options[1])) {
              $this->setOptions([$optionName => $options[1]]);
            } else {
              $this->setErrors(['option_not_argument'  => 'У опции нет аргумента']);
            }
          } else {
            $this->setErrors(['option_not_found' => 'Опция не найдена']);
          }
        } else {
          $this->setErrors(['incorrect_option_format' => 'Некорректный формат опции']);
        }
      }
    }

    if ($notOptions === true) {
      $this->setErrors(['option_not_found' => 'Отсутствуют опции']);
    }
    return empty($this->getErrors());
  }

  public function parseArguments(): bool
  {
    $requiredArguments = $this->getPossibleArguments();
    $notArgument = true;

    foreach ($this->commands as $key => $command) {
      if (mb_substr_count($command, '{') > 0){
        $notArgument = false;
        $argument = str_replace(['{', '}'], '', $command);
        if (!empty($argument)) {
          if (in_array($argument, $requiredArguments)) {
            $this->setArguments($argument);
          } else {
            $this->setErrors(['argument_not_found' => 'Присутствует неизвестный аргумент']);
          }
        } else {
          $this->setErrors(['missing_arguments' => 'Отсутствуют аргументы']);
        }
      } elseif ($key > 1 && mb_substr_count($command, '[') < 1) {
        $notArgument = false;
        $this->setArguments($command);
      }
    }

    if ($notArgument === true) {
      $this->setErrors(['missing_arguments' => 'Отсутствуют аргументы']);
    }

    return empty($this->getErrors());
  }

  public function setErrors(array $error): bool
  {
    if (!isset($this->errors)) {
      $this->errors = [];
    }
    $this->errors = array_merge($this->errors, $error);

    return true;
  }

  public function setArguments(string $argument): bool
  {
    $this->commandArgument[] = $argument;

    return true;
  }

  public function setOptions(array $option): bool
  {
    $this->commandOptions[] = $option;

    return true;
  }

  public function result(): string
  {
    $result = '';
    if (!empty($this->getErrors())) {
      $result = implode("\n", $this->getErrors()) . "\n";
      $result .= $this->getDescriptionCommands();
    }

    return $result;
  }
}