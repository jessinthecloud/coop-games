@extends('layouts.app')

@section('title')
    {{ $game['name'] }} |
@endsection

@section('content')
    <?php //dump($game['multiplayer_modes']); ?>
    <div id="" class="
        md:mt-8
    ">

        <div id="details-wrapper" class="game-details
            pb-12 flex
        ">

            <div class="cover
                flex-none hidden
                lg:block
            ">
                <img src="{{ $game['cover_url'] }}" alt="{{ $game['name'] }} Cover Art">
            </div>

            <div id="details">
                <!-- title -->
                <h2 id="title" class="font-semibold text-4xl leading-tight">
                    {{ $game['name'] }}
                </h2>
                <div class="cover
                    mt-4 float-left
                    lg:hidden
                ">
                    <img src="{{ $game['cover_url'] }}" alt="{{ $game['name'] }} Cover Art">
                </div>

                <!-- multiplayer_modes -->
                <div id="" class="related-details-card mt-4 text-gray-400 inline-block w-full
                    md:w-1/2 md:float-right
                ">
                    @if(!empty($game['multiplayer_modes']))
                        <div class="modes-wrapper flex flex-col lg:flex-row">
                            @if(!empty($game['multiplayer_modes'][0]['coop-types']))
                                <div class="coop-details mb-4 lg:mr-16 lg:mb-0">
                                    <h3 class="text-lg font-bold mb-1 uppercase tracking-wide">
                                        Co-op Modes
                                    </h3>
                                    @foreach($game['multiplayer_modes'] as $modes)
                                        <ul>
                                            @foreach($modes['coop-types'] as $ctype)
                                                <li>
                                                    <i class="fas fa-check-square"></i>
                                                    <span class="coop-label">
                                                        {{ $ctype['label'] }}
                                                        @if(!empty($ctype['max']))
                                                            ({{ $ctype['max'] }} player max)
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                            @endif {{-- end coop --}}

                            {{--@if(!empty($game['multi_info']))
                                    <div class="multi-details">
                                        <h3 class="text-xl font-bold mb-1">Multiplayer Details</h3>
                                        <ul>
                                            @if(is_array($game['multi_info']))
                                                @foreach($game['multi_info'] as $mkey => $mtype)
                                                    @if(is_array($mtype))
                                                        <li>
                                                            @if(!empty($mtype['value']))
                                                                <i class="fas fa-check-square text-purple-600"></i>
                                                            @else
                                                                <i class="fas fa-window-close"></i>
                                                            @endif
                                                            <span class="multi-label">
                                                    {{ $mtype['label'] }}
                                                                @if(($mkey == 'online' || $mkey == 'offline') && !empty($mtype['value']))
                                                                    ({{ $mtype['value'] }} player max)
                                                                @endif
                                                </span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div> <!-- end mutli details -->
                                @endif--}}
                        </div>
                    @endif
                    {{-- end if multi or coop --}}
                </div>

                <div id="" class="related-details-card mt-4 text-gray-400 inline-block w-full
                    md:w-1/2 md:float-right
                ">
                    <!-- platforms -->
                    @if($game['platforms'])
                        <div id="platforms" class="details-card-section">
                            <h4>Platforms</h4>
                            <span>{!! $game['platforms'] !!}</span>
                        </div>
                    @endif

                    <!-- genres -->
                    @if($game['genres'])
                        <div id="genres" class="details-card-section">
                            <h4>Genres</h4>
                            <span>{!! $game['genres'] !!}</span>
						</div>
                    @endif

                    @if(!empty($game['companies']))
                        <!-- companies -->
                        <div id="companies-wrapper" class="details-card-section">
                            @if($game['companies']['devs'])
                                <div id="developers" class="companies">
                                    <h4>Developers</h4>
                                    <span>{!! $game['companies']['devs'] !!}</span>
                                </div>
                            @endif

                            @if($game['companies']['pubs'])
                                <div id="publishers" class="companies">
                                    <h4>Publishers</h4>
                                    <span>{!! $game['companies']['pubs'] !!}</span>
                                </div>
                            @endif
                        </div>
                        <!-- companies-wrapper -->
                    @endif
                </div>
            <!-- related-details-card -->

                @if(!empty($game['first_release_date']))
                    <div class="first-release
                        flex flex-wrap items-center mt-4">
                        <span class="font-semibold">First Released</span>: {{ $game['first_release_date'] }}
                    </div>
                @endif

                <div class="flex flex-wrap justify-between items-center mt-8

                ">
                    <!-- member score -->
                    <div class="flex items-center flex-grow">
                        <div id="member-rating" class="rating
                            w-16 h-16 bg-gray-800 rounded-full relative
                        ">
                            @if($game['rating'])
                                {{-- add to the "scripts" stack (from app.blade.php) --}}
                                @push('scripts')
                                    @include('partials._rating', [
                                        'slug' => 'member-rating',
                                        'rating' => $game['rating'],
                                        'event' => null,
                                        'count' => 0,
                                    ])
                                @endpush
                                 {{--<div class="font-semibold text-xs flex justify-center items-center h-full">
                                    {{ $game['rating'] }}
                                 </div>--}}
                            @endif
                        </div>
                        <div class="rating-label">Member<br>Score</div>
                    </div><!-- end member score -->
                    <!-- critic score -->
                    <div class="flex items-center ml-12 flex-grow
                        md:ml-6
                    ">
                        <div id="critic-rating" class="rating
                            w-16 h-16 bg-gray-800 rounded-full relative
                        ">
                            @if($game['aggregated_rating'])
                                @push('scripts')
                                    @include('partials._rating', [
                                        'slug' => 'critic-rating',
                                        'rating' => $game['aggregated_rating'],
                                        'event' => null,
                                        'count' => 1,
                                    ])
                                @endpush
                                {{--<div class="font-semibold text-xs flex justify-center items-center h-full">
                                    {{ $game['rating'] }}
                                 </div>--}}
                            @endif
                        </div>
                        <div class="rating-label">Critic<br>Score</div>
                    </div> <!-- end critic score -->

                    <!-- socials -->
                    @if(!empty($game['websites']) || !empty($game['website']))
                        <div class="flex items-center space-x-4 mt-4 lg:mt-0 lg:ml-12">
                            <!-- website -->
                            @if(!empty($game['website']))
                                <div class="w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                                    <a href="{{ $game['website'] }}"
                                       class="transition ease-in-out duration-150
                                            hover:text-purple-400
                                    ">
                                        {!! config('services.svg.website') !!}
                                    </a>
                                </div>
                            @endif
                        <!-- instagram -->
                            @if(!empty($game['social']['instagram']))
                                <div class="w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                                    <a href="{{ $game['social']['instagram']['url'] }}" class="transition ease-in-out duration-150 hover:text-purple-400">{!! config('services.svg.instagram') !!}</a>
                                </div>
                            @endif
                        <!-- twitter -->
                            @if(!empty($game['social']['twitter']))
                                <div class="w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                                    <a href="{{ $game['social']['twitter']['url'] }}" class="transition ease-in-out duration-150 hover:text-purple-400">{!! config('services.svg.twitter') !!}</a>
                                </div>
                            @endif
                        <!-- facebook -->
                            @if(!empty($game['social']['facebook']))
                                <div class="w-8 h-8 bg-gray-800 rounded-full flex justify-center items-center">
                                    <a href="{{ $game['social']['facebook']['url'] }}" class="transition ease-in-out duration-150 hover:text-purple-400">{!! config('services.svg.facebook') !!}</a>
                                </div>
                            @endif
                        </div>
                    @endif
                    <!-- end socials -->
                </div>
                <!-- end scores-container -->

                <p class="mt-12">
                    {{ $game['summary'] }}
                </p>

                @if(!empty($game['store_links'][0]['url']))
                <!-- links to digital marketplace -->
                    <div class="flex flex-wrap">
                        @foreach($game['store_links'] as $store => $store_link)
                            <a 
                               class="mt-6 mr-4 text-gray-200 transition ease-in-out duration-150
                                   hover:opacity-50 hover:text-purple-400" 
                               target="_blank" 
                               rel="noopener nofollow" 
                               href="{{ $store_link['url'] }}"
                            >
                                {!! $store_link['icon'] !!}
                            </a>
                        @endforeach
                    </div>
                    <!-- end store links -->
                @endif

                @if(!empty($game['trailer']))
                    <div class="mt-12" x-data="{ isTrailerModalVisible: false }">
                    {{-- <a href="{{ $game['trailer'] }}" class="inline-flex bg-purple-500 text-white font-semibold px-4 py-4 hover:bg-purple600 rounded transition ease-in-out duration-150" target="_blank" rel="nofollow noopener">
                        <svg class="w-6 fill-current mr-2" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"></path><path d="M10 16.5l6-4.5-6-4.5v9zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path></svg>
                        Play Trailer
                    </a> --}}
                    <!-- play video button -->
                        <button
                                @click="isTrailerModalVisible = true"
                                class="flex bg-purple-500 text-white font-semibold px-4 py-4 hover:bg-purple-600 rounded transition ease-in-out duration-150"
                        >
                            <svg class="w-6 fill-current" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"></path><path d="M10 16.5l6-4.5-6-4.5v9zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path></svg>
                            <span class="ml-2">Play Trailer</span>
                        </button>

                        {{-- if the var is true, we create the element, otherwise, remove it from DOM entirely --}}
                        <template x-if="isTrailerModalVisible">
                            <!-- modal to play video -->
                            <div
                                    class="z-50 fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto"
                                    style="background-color: rgba(0, 0, 0, .5);"
                            >
                                <div class="container mx-auto lg:px-32 rounded-lg overflow-y-auto">
                                    <div class="bg-gray-900 rounded">
                                        <div class="flex justify-end pr-4 pt-2">
                                            <button
                                                    @click="isTrailerModalVisible = false"
                                                    @keydown.escape.window="isTrailerModalVisible = false"
                                                    class="text-3xl leading-none hover:text-gray-300"
                                            >
                                                &times;
                                            </button>
                                        </div>
                                        <div class="modal-body px-8 py-8">
                                            <div class="responsive-container overflow-hidden relative" style="padding-top: 56.25%">
                                                <iframe width="560" height="315" class="responsive-iframe absolute top-0 left-0 w-full h-full" src="{{ $game['trailer'] }}" style="border:0;" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>


                    </div> <!-- end trailer button -->
                @endif
            </div> <!-- end details -->
        </div> <!-- end game container -->

        <!-- screenshots -->
        @if(!empty($game['screenshots'] && isset($game['screenshots'][0]['huge'])))
            {{-- set another var called image so that we can keep track of which image
                    is opened and needs closing for each event --}}
            <div id="screenshots"
                class="images-container border-t border-gray-800 pt-8 mb-12"
                x-data="{ isImageModalVisible: false, image: '' }"
            >
                <h2 class="subtitle">Images</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mt-8">
                    @foreach ($game['screenshots'] as $screenshot)
                        <div class="screenshot">
                            <a
                                href="#"
                                    @click.prevent="
	                                isImageModalVisible = true

	                        		// set currently opened image
	                                image='{{ $screenshot['huge'] }}'
	                            "
                                class="round-border"
                            >
                                <img src="{{ $screenshot['big'] }}" alt="screenshot"
                                     class="round-border
                                ">
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- image modal -->
                <template x-if="isImageModalVisible">
                    <div
                        style="background-color: rgba(0, 0, 0, .5);"
                        class="screenshot-modal
                            z-50 fixed top-0 left-0 w-full h-full flex items-center shadow-lg overflow-y-auto"
                    >
                        <div class="container mx-auto rounded-lg overflow-y-auto lg:px-32">
                            <div class="screenshot-modal-bg round-border">
                                <div class="flex justify-end pr-4 pt-2">
                                    <button
                                        class="text-3xl leading-none hover:text-gray-300"
                                        @click="isImageModalVisible = false"
                                        @keydown.escape.window="isImageModalVisible = false"
                                    >
                                        &times;
                                    </button>
                                </div>
                                <div class="modal-body px-8 py-8">
                                    <img :src="image" alt="screenshot">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.screenshot-modal -->
                </template>
            </div> <!-- end images-container -->
        @endif

    <!-- similar games -->
        @if(!empty($game['similar_games']))
            <div id="similar-games" class="border-t border-gray-800 pt-8 mb-12">
                <h2 class="subtitle">
                    Similar Co-op Games
                </h2>

                <div class="similar-games-wrapper">
                    @foreach($game['similar_games'] as $i => $sgame)
                        <x-game-card :game="$sgame" :count="$i" />
                    @endforeach
                </div> <!-- end similar games -->

            </div> <!-- end similar games container -->
        @endif
    </div> <!-- end container -->
@endsection