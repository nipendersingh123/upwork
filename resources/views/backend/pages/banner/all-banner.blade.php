@extends('backend.layout.master')
@section('title', __('All Banners'))
@section('style')
    <x-media.css/>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('All Banners') }}</h4>
                            <div class="btn-wrapper">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_banner_modal">{{__('Add New Banner')}}</button>
                            </div>
                        </div>
                        <x-bulk-action.bulk-action />
                        <div class="customMarkup__single__inner mt-4">
                            <!-- Table Start -->
                            <div class="custom_table style-04">
                                <table class="DataTable_activation">
                                    <thead>
                                    <tr>
                                        <th class="no-sort">
                                            <div class="mark-all-checkbox">
                                                <input type="checkbox" class="all-checkbox">
                                            </div>
                                        </th>
                                        <th>{{__('Image')}}</th>
                                        <th>{{__('Category')}}</th>
                                        <th>{{__('Project')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($banners as $data)
                                        <tr>
                                            <td> <x-bulk-action.bulk-delete-checkbox :id="$data->id"/> </td>
                                            <td>
                                                {!! render_image_markup_by_attachment_id($data->image) !!}
                                            </td>
                                            <td>{{optional($data->category)->category}}</td>
                                            <td>{{optional($data->project)->title}}</td>
                                            <td>
                                                <x-status.table.active-inactive :status="$data->status"/>
                                            </td>
                                            <td>
                                                <a tabindex="0" class="btn btn-danger btn-xs mb-3 mr-1 swal_delete_button">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                                <form method='post' action='{{route('admin.mobile.app.banner.delete',$data->id)}}' class="d-none">
                                                    <input type='hidden' name='_token' value='{{csrf_token()}}'>
                                                    <br>
                                                    <button type="submit" class="swal_form_submit_btn d-none"></button>
                                                </form>
                                                <a href="#"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#edit_banner_modal"
                                                   class="btn btn-primary btn-xs mb-3 mr-1 edit_banner_btn"
                                                   data-id="{{$data->id}}"
                                                   data-image="{{$data->image}}"
                                                   data-image_url="{{get_attachment_image_by_id($data->image)['img_url'] ?? ''}}"
                                                   data-category_id="{{$data->category_id}}"
                                                   data-project_id="{{$data->project_id}}"
                                                   data-status="{{$data->status}}"
                                                >
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="add_banner_modal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBannerModalLabel">{{__('Add New Banner')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('admin.mobile.app.banner.settings')}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="image">{{__('Image')}}</label>
                            <div class="media-upload-btn-wrapper">
                                <div class="img-wrap"></div>
                                <input type="hidden" name="image">
                                <button type="button" class="btn btn-info media_upload_form_btn"
                                        data-btntitle="{{__('Select Image')}}"
                                        data-modaltitle="{{__('Upload Image')}}" data-bs-toggle="modal"
                                        data-bs-target="#media_upload_modal">
                                    {{__('Upload Image')}}
                                </button>
                            </div>
                            <small>{{__('Recommended image size 1920x1080')}}</small>
                        </div>
                        <div class="form-group">
                            <label for="category_id">{{__('Category')}}</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">{{__('Select Category')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="project_id">{{__('Project')}}</label>
                            <select name="project_id" id="project_id" class="form-control">
                                <option value="">{{__('Select Project')}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">{{__('Status')}}</label>
                            <select name="status" class="form-control" id="status">
                                <option value="1">{{__('Publish')}}</option>
                                <option value="0">{{__('Draft')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Add New Banner')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_banner_modal" tabindex="-1" aria-labelledby="editBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBannerModalLabel">{{__('Edit Banner')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('admin.mobile.app.banner.edit')}}" id="edit_banner_form" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="banner_id">
                        <div class="form-group">
                            <label for="edit_image">{{__('Image')}}</label>
                            <div class="media-upload-btn-wrapper">
                                <div class="img-wrap"></div>
                                <input type="hidden" id="edit_image" name="image">
                                <button type="button" class="btn btn-info media_upload_form_btn"
                                        data-btntitle="{{__('Select Image')}}"
                                        data-modaltitle="{{__('Upload Image')}}" data-bs-toggle="modal"
                                        data-bs-target="#media_upload_modal">
                                    {{__('Upload Image')}}
                                </button>
                            </div>
                            <small>{{__('Recommended image size 1920x1080')}}</small>
                        </div>
                        <div class="form-group">
                            <label for="edit_category_id">{{__('Category')}}</label>
                            <select name="category_id" id="edit_category_id" class="form-control">
                                <option value="">{{__('Select Category')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_project_id">{{__('Project')}}</label>
                            <select name="project_id" id="edit_project_id" class="form-control">
                                <option value="">{{__('Select Project')}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">{{__('Status')}}</label>
                            <select name="status" id="edit_status" class="form-control">
                                <option value="1">{{__('Publish')}}</option>
                                <option value="0">{{__('Draft')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-media.markup/>
@endsection
@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <x-bulk-action.bulk-delete-js :url="route('admin.mobile.app.banner.bulk.action')"/>
    <x-media.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function() {
                // Fetch projects on category change for Add Form
                $(document).on('change', '#category_id', function() {
                    let category_id = $(this).val();
                    $.ajax({
                        url: "{{ route('admin.mobile.app.banner.project.by.category') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            category_id: category_id
                        },
                        success: function(data) {
                            let project_select = $('#project_id');
                            project_select.empty();
                            project_select.append('<option value="">{{__('Select Project')}}</option>');
                            $.each(data, function(key, value) {
                                project_select.append('<option value="' + value.id + '">' + value.title + '</option>');
                            });
                        }
                    });
                });

                // Edit Banner
                $(document).on('click', '.edit_banner_btn', function() {
                    let el = $(this);
                    let id = el.data('id');
                    let image = el.data('image');
                    let image_url = el.data('image_url');
                    let category_id = el.data('category_id');
                    let project_id = el.data('project_id');
                    let status = el.data('status');

                    let form = $('#edit_banner_form');
                    form.find('#banner_id').val(id);
                    form.find('#edit_status').val(status);
                    form.find('#edit_category_id').val(category_id);

                    // Pre-fill image
                    if (image != '') {
                        form.find('.media-upload-btn-wrapper .img-wrap').html('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' + image_url + '" > </div></div></div>');
                        form.find('.media-upload-btn-wrapper input').val(image);
                        form.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                    }

                    // Fetch projects and select the correct one
                    $.ajax({
                        url: "{{ route('admin.mobile.app.banner.project.by.category') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            category_id: category_id
                        },
                        success: function(data) {
                            let project_select = $('#edit_project_id');
                            project_select.empty();
                            project_select.append('<option value="">{{__('Select Project')}}</option>');
                            $.each(data, function(key, value) {
                                let selected = (value.id == project_id) ? 'selected' : '';
                                project_select.append('<option value="' + value.id + '" ' + selected + '>' + value.title + '</option>');
                            });
                        }
                    });
                });

                // Fetch projects on category change for Edit Form
                $(document).on('change', '#edit_category_id', function() {
                    let category_id = $(this).val();
                    $.ajax({
                        url: "{{ route('admin.mobile.app.banner.project.by.category') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            category_id: category_id
                        },
                        success: function(data) {
                            let project_select = $('#edit_project_id');
                            project_select.empty();
                            project_select.append('<option value="">{{__('Select Project')}}</option>');
                            $.each(data, function(key, value) {
                                project_select.append('<option value="' + value.id + '">' + value.title + '</option>');
                            });
                        }
                    });
                });

            });
        })(jQuery);
    </script>
@endsection
