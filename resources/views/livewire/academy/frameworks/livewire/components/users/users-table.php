<?php

/**
 * (ɔ) LARAVEL.Sillo.org - 2015-2024
 */

use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class() extends Component {
	// Source: https://www.youtube.com/watch?v=zPNdejemUtg

	use WithPagination;

	public $headers;
	public $roles;
	public string $search = '';
	public $sortBy        = [
		'column'    => 'id',
		'direction' => 'asc',
	];

	// public $users;
	public $queryStringOutput = [];
	protected $queryString    = [
		'search' => ['except' => ''],
		'sortBy' => ['except' => ['column' => 'id', 'direction' => 'asc']],
	];

	public function mount()
	{
		$this->headers = [
			['key' => 'id', 			 'label' => '#'],
			['key' => 'name', 		 'label' => __('Name')],
			['key' => 'role', 		 'label' => __('Role')],
			['key' => 'isStudent', 'label' => __('Status')],
			['key' => 'valid', 		 'label' => __('Valid')],
			['key' => '', 				 'label' => ''],
		];

		$this->roles = [
			'admin' => ['Administrator', 'error'],
			'redac' => ['Redactor', 		 'warning'],
			'user'  => ['User'],
		];

		// $this->queryStringOutput['search'] = $this->search;
		$this->updatedSearch(false);
		$this->updatedSortBy();
		$this->updatedPage();
	}

	// For custom pagination view
	// public function paginationView()
	// {
	// 	return 'livewire.pagination';
	// }

	public function updatedSearch($resetPage = true)
	{
		Debugbar::addMessage("New search: {$this->search}");
		if ($resetPage) {
			$this->setPage(1);
			unset($this->queryStringOutput['page']);
		}
		$this->queryStringOutput['search'] = $this->search;
		if ('' == $this->search) {
			unset($this->queryStringOutput['search']);
		}
	}

	public function updatedSortBy($value = 'id', $key = 'asc')
	{
		// dump($this->sortBy['column']);
		// Debugbar::warning($this->sortBy['direction']);

		// To avoid displaying the new sort information twice
		if ('column' === $key || 'direction' === $key) {
			Debugbar::addMessage("New sort: By {$this->sortBy['column']}, {$this->sortBy['direction']}");
			$this->queryStringOutput['sortBy']['column']    = $this->sortBy['column'];
			$this->queryStringOutput['sortBy']['direction'] = $this->sortBy['direction'];
		} else {
			$this->dispatch('console-log', ['message' => [$this->sortBy['column'], $this->sortBy['direction']]]);
			
			if ('id' == $this->sortBy['column'] && 'asc' == $this->sortBy['direction']) {
				unset($this->queryStringOutput['sortBy']);
			} else {
				$this->queryStringOutput['sortBy'] = [
					$this->sortBy['column'],
					$this->sortBy['direction'],
				];
			}
		}
	}

	public function updatedPage()
	{
		$currentPage = $this->getPage();
		Debugbar::addMessage("New page: {$currentPage}");
		if ($currentPage > 1) {
			$this->queryStringOutput['page'] = $this->getPage();
		}
	}

	public function setOrderField(string $name)
	{
		if ($name === $this->orderField) {
			$this->orderDirection = 'asc' === $this->orderDirection ? 'desc' : 'asc';
		} else {
			$this->orderField = $name;
			$this->reset('orderDirection');
		}
	}

	public function with()
	{
		$users = User::search($this->search)
			->orderBy(...array_values($this->sortBy))
			->paginate(4);

		return [
			'users' => $users,
		];
	}
};