<?php

namespace Varrcan\SeoRegions;

if (!\defined('ABSPATH')) {
    die;
}

/**
 * Class SeoRegionsLoader
 * @package Varrcan\SeoRegions
 */
trait SeoRegionsLoader
{
    protected $actions = [];
    protected $filters = [];

    /**
     * Хуки
     *
     * @param     $hook
     * @param     $component
     * @param     $callback
     * @param int $priority
     * @param int $args
     */
    public function addAction($hook, $component, $callback, $priority = 10, $args = 1)
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $args);
    }

    /**
     * Фильтры
     *
     * @param     $hook
     * @param     $component
     * @param     $callback
     * @param int $priority
     * @param int $args
     */
    public function addFilter($hook, $component, $callback, $priority = 10, $args = 1)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $args);
    }

    /**
     * Аргументы
     *
     * @param $hooks
     * @param $hook
     * @param $component
     * @param $callback
     * @param $priority
     * @param $args
     *
     * @return array
     */
    private function add($hooks, $hook, $component, $callback, $priority, $args):array
    {

        $hooks[] = [
            'hook'      => $hook,
            'component' => $component,
            'callback'  => $callback,
            'priority'  => $priority,
            'args'      => $args
        ];

        return $hooks;
    }

    /**
     * Добавление хуков и фильтров
     */
    public function run()
    {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['args']);
        }
    }
}
