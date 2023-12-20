@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light">
                            <thead>
                                <tr>
                                    <th>@lang('Story')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('created At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($successStories as $story)
                                    <tr>
                                        <td>
                                            <div class="user thumb">
                                                <div class="thumb w-100">
                                                    <img src="{{ getImage(getFilePath('success') . '/' . $story->image, getFileSize('success')) }}" alt="@lang('Image')">
                                                    <span> {{ strLimit($story->title, 35) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ __($story->category->name) }}</td>
                                        <td>
                                            {{ showDateTime($story->created_at) }}
                                            <span class="d-block">{{diffForHumans($story->created_at)}}</span>
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.success.story.detail',$story->id) }}"
                                                    class="btn btn-sm btn-outline--info ms-1">
                                                     <i class="la la-desktop"></i>@lang('Details')
                                                 </a>
                                                 <a href="{{ route('admin.success.story.edit', $story->id) }}"
                                                     class="btn btn-sm btn-outline--primary">
                                                     <i class="la la-pen"></i>@lang('Edit')
                                                 </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.success.story.delete', $story->id) }}"
                                                    data-question="@lang('Are you sure to delete this success story?')">
                                                    <i class="la la-trash"></i>@lang('Delete')
                                                </button>

                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
                @if ($successStories->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($successStories) @endphp
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
<x-search-form />
<a href="{{ route('admin.success.story.create') }}" class="btn btn-sm btn-outline--primary"> <i
        class="las la-plus"></i>@lang('Add New')</a>
@endpush
