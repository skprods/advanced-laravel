<?php

namespace SKprods\AdvancedLaravel\Handlers;

use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\ConsoleOutput as BaseConsoleOutput;

class ConsoleOutput
{
    private ?BaseConsoleOutput $output = null;

    public function __construct()
    {
        $this->setOutput();
    }

    public function info(string $message)
    {
        $this->line($message, 'info');
    }

    public function comment(string $message)
    {
        $this->line($message, 'comment');
    }

    public function error(string $message)
    {
        $this->line($message, 'error');
    }

    protected function line(string $message, string $style = null)
    {
        $styled = ($style) ? "<$style>$message</$style>" : $message;
        $this->output->writeln($styled);
    }

    /**
     * Кастомизированное сообщение
     *
     * @param string|null $foreground   - цвет текста
     * @param string|null $background   - цвет фона
     * @param array $options            - дополнительные опции (bold, underscore, blink, reverse, conceal)
     */
    public function message(string $message, string $foreground = null, string $background = null, array $options = [])
    {
        $styles = [];

        if ($foreground) {
            $styles[] = "fg=$foreground";
        }

        if ($background) {
            $styles[] = "bg=$background";
        }

        if (!empty($options)) {
            $styles[] = "options=" . implode(',', $options);
        }

        $style = implode(';', $styles);
        $styledMessage = empty($style) ? $message : "<$style>$message</>";

        $this->output->writeln($styledMessage);
    }

    public function bar(int $max = 0, float $minSecondsBetweenRedraws = 1 / 25): ProgressBar
    {
        return new ProgressBar($this->output, $max, $minSecondsBetweenRedraws);
    }

    /**
     * Вывод таблицы в консоль
     *
     * @param array $headers                - заголовки таблицы
     * @param Arrayable|array $rows         - строки таблицы
     * @param TableStyle|string $tableStyle - кастомный стиль таблицы
     * @param array $columnStyles           - кастомные стили для колонок в формате [columnIndex => TableStyle]
     */
    public function table(array $headers, $rows, $tableStyle = 'default', array $columnStyles = [])
    {
        $table = new Table($this->output);

        if ($rows instanceof Arrayable) {
            $rows = $rows->toArray();
        }

        $table->setHeaders($headers)->setRows($rows)->setStyle($tableStyle);

        foreach ($columnStyles as $columnIndex => $columnStyle) {
            $table->setColumnStyle($columnIndex, $columnStyle);
        }

        $table->render();
    }

    private function setOutput()
    {
        if (!$this->output) {
            $this->output = app(BaseConsoleOutput::class);
        }
    }
}