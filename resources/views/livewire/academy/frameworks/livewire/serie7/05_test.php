<?php

/**
 * (ɔ) LARAVEL.Sillo.org - 2015-2024
 */

use Livewire\Volt\Component;

new class() extends Component {
	public $subtitle = 'Test';
	public $limit    = 5;

	public function mount($loadMore = true, $offset = 0)
	{
		$this->dispatch('update-subtitle', newSubtitle: $this->subtitle);
		
/**
 * (ɔ) LARAVEL.Sillo.org - 2015-2024
 */

logger('Dispatching update-subtitle event (for ' . $this->subtitle . ')');

	}

	public function with(): array
	{
		return [
		];
	}
};
