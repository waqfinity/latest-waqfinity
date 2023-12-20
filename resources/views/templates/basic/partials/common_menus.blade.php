<li><a class="{{ menuActive('home') }}" href="{{ route('home') }}">@lang('HOME')</a></li>
@foreach ($pages as $data)
<li><a class="{{ menuActive('pages', null, $data->slug) }}" href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
@endforeach
<li><a class="{{ menuActive('campaign.index') }}" href="{{ route('campaign.index') }}">@lang('CAMPAIGNS')</a></li>
<!-- <li><a class="{{ menuActive('volunteer.index') }}" href="{{ route('volunteer.index') }}">@lang('VOLUNTEERS')</a></li> -->
<li><a class="{{ menuActive('success.story.archive') }}" href="{{ route('success.story.archive') }}">@lang('SUCCESS STORY')</a></li>
