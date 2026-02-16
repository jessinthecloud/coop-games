@props(['platforms' => []])
@if(!empty($platforms))<?php
    echo collect($platforms)->map(function ($platform) {
        return !empty($platform['abbreviation'])
            ? $platform['abbreviation']
            : (!empty($platform['name']) ? $platform['name'] : '');
    })->filter()->implode(', ');
?>@endif