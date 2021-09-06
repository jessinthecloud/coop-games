<aside id="game-details-sidebar" class="w-full ml-0 md:w-1/2 md:ml-4">
    <!-- multiplayer_modes -->
    <div id="" class="related-details-card mt-4 text-gray-400 inline-block w-full

    ">
        @if(!empty($game['multiplayer_modes']))
        <div class="modes-wrapper flex flex-col lg:flex-row">
            @if(!empty($game['multiplayer_modes'][0]['coop-types']))
            <div class="coop-details
                w-full
            ">
                <h3 class="text-lg font-bold mb-1 uppercase tracking-wide w-full">
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

    <div id="" class="related-details-card
        mt-4 text-gray-400 inline-block w-full
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
</aside>
<!-- end details aside -->