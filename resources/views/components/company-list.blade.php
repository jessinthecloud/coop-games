@props(['companies' => []])
@if(!empty($companies))<?php
    echo collect($companies)->map(function ($company) {
        return !empty($company['company']['slug'])
            ? '<a href="' . route('platforms.show', ['slug' => $company['company']['slug']]) . '" class="text-purple-500 underline transition ease-in-out duration-150 hover:text-purple-300 hover:no-underline">'
                . e($company['company']['name'])
                . '</a>'
            : '';
    })->filter()->implode(', ');
?>@endif