<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SortForm extends Component
{
    /**
     * Create a new component instance.
     */
    public array $sort;
    public ?array $addOptions;
    public function __construct($sort, $addOptions = null)
    {
        $this->sort = $sort;
        $this->addOptions = $addOptions;
    }

    public array $options = [
        'created_at' => '投稿日順',
        'updated_at' => '更新日順',
        'title' => 'タイトル順',
    ];

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (isset($this->addOptions)) {
            foreach ($this->addOptions as $addOption => $item) {
                $this->options[$addOption] = $item;
            }
        }

        return view('components.sort-form');
    }
}
