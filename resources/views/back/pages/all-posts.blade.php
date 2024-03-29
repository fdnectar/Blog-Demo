@extends('back.layouts.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'All Posts')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">All Post</h2>
            </div>
        </div>
    </div>
</div>
<hr>

@livewire('all-posts')


@endsection

@push('custom-scripts')
    <script>
        window.addEventListener('deletePost', function(event) {
        swal.fire({
            title:event.detail.title,
            imageWidth:48,
            imageHeight:48,
            html:event.detail.html,
            showCloseButton:true,
            showCancelButton:true,
            cancelButtonText:'cancel',
            confirmButtonText:'Yes, Delete',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            width:300,
            allowOutsideClick:false
        }).then(function(result){
            if(result.value) {
                Livewire.emit('deletePostAction', event.detail.id);
            }
        });
    });
    </script>
@endpush