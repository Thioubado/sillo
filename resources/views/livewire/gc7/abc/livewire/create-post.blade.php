<?php

/**
 * (ɔ) LARAVEL.Sillo.org - 2015-2024
 */

use App\Models\PostGc7;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Mary\Traits\Toast;

new
#[Title('New Post')]
#[Layout('components.layouts.gc7')]
class extends Component {
	use Toast;

	#[Rule('required', message: 'Yo, add a title!')]
	#[Rule('min:3', message: 'Yo, more than 2 chars, please!')]
	public $title = '';

	#[Rule('required', as: 'content (textarea)')]
	public $content = '';

	public function save()
	{
		$this->validate();
		PostGc7::create([
			'title'   => $this->title,
			'content' => $this->content,
		]);
		$this->success('Post added !');
		$this->redirect('/t/blog');
	}
};?>

 <div>
     <x-header title="New FORM" shadow separator progress-indicator>
     </x-header>

     Current title: <span x-text="$wire.title.toUpperCase()"></span>

     @php
         $contentLength = strlen($content);
         $maxChars = env('APP_MAX_NUMBER_OF_CHARS_IN_COMMENTS_FORM');
         $messageKey = $contentLength >= $maxChars ? 'All chars used :m' : 'Chars2 used: :n :m';
     @endphp

     <form wire:submit="save">
         @error('title')
             Attention: <em>{{ $message }}</em><br>
         @enderror

         <label for="title">
             <span>Title</span>
             <x-input class="mt-1 mb-2" type="text" wire:model='title'></x-input>
         </label>

         <label for="content">
             <span>Content</span>
             <x-textarea class="mt-1" name="" id="" cols="30" rows="7"
                 wire:model.live='content' maxlength="{{ $maxChars }}"></x-textarea>
         </label>
         <span
             x-text="'{{ trans_choice($messageKey, $contentLength, ['n' => $contentLength, 'm' => $maxChars]) }}'"></span>

         <div class="text-right w-full">
             <x-button type="submit" class="btn-primary mt-2 mr-5">
             Save</x-button>
         </div>
     </form>
 </div>
