@extends('layout.master')

@push('plugin-styles')
    <!-- Add any plugin styles if required -->
@endpush

@push('style')
<style>
    .treeview ul {
        list-style-type: none;
        padding-left: 20px;
    }

    .treeview li {
        margin: 5px 0;
        cursor: pointer;
    }

    .treeview li span {
        display: inline-block;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
        background-color: #f9f9f9;
    }

    .treeview li span:hover {
        background-color: #e0e0e0;
    }
</style>
@endpush

@php 
    $user_id = Auth::user()->id;
@endphp

@section('content')
<div class="treeview">
    <ul>
        <li>
            <span>Parent 1</span>
            <ul>
                <li><span>Child 1.1</span></li>
                <li><span>Child 1.2</span></li>
            </ul>
        </li>
        <li>
            <span>Parent 2</span>
            <ul>
                <li><span>Child 2.1</span></li>
                <li><span>Child 2.2</span></li>
            </ul>
        </li>
    </ul>
</div>
@endsection

@push('plugin-scripts')
    <!-- Add any plugin scripts if required -->
@endpush

@push('custom-scripts')
    <!-- Add any custom scripts if required -->
@endpush
