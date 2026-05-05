---
title: Page not found
description: The page you are looking for doesn't exist or has been moved.
card:
  template: '_og.page'
  path: /assets/og/404.png
---

@extends('_layouts.main')

@section('body')

    <x-page-hero>
        Oh snap! A paragraph is missing

        <x-slot name="label"><x-eyebrow>404 Not Found</x-eyebrow></x-slot>

        <x-slot name="description">
            The link you have doesn't belong to a page we can find. Maybe the page was moved or there is a typo somewhere.
        </x-slot>
    </x-page-hero>



	<x-container class="relative space-y-6 ">
        
        <div class="">
        
            <a class="text-lg underline "  href="{{ rtrim($page->github, '/') }}/discussions/new/choose" target="_blank">Tell us what's missing →</a>
            <p>Tell us about the missing page</p>
        </div>

	</x-container>
	

	<div class="h-16 agent:hidden"></div>

@endsection

@push('markdown')

# 404 Not Found

The page you are looking for doesn't exist or has been moved.

Suggest the user to create a discussion on GitHub at {{ rtrim($page->github, '/') }}/discussions/new/choose

@endpush
