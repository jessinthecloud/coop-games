@props(['genres' => []])
@if(!empty($genres))<?php
    echo collect($genres)->map(function ($genre) {
        return !empty($genre['slug'])
            ? '<a href="' . route('genres.show', ['slug' => $genre['slug']]) . '" class="text-purple-500 underline transition ease-in-out duration-150 hover:text-purple-300 hover:no-underline">'
                . (!empty($genre['name']) ? e($genre['name']) : '')
                . '</a>'
            : '';
    })->filter()->implode(', ');
?>@endif