@extends('layouts.admin')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="content-wrapper">
        <!-- Responsive Table -->
        @foreach($files as $key => $org)
    
        <div class="card">
            <div class="row">
                <h5 class="card-header">{{$key}}</h5>
            </div>
            <div class="table-responsive text-nowrap">

                <table class="table">
                    <thead>
                        <tr class="text-nowrap">
                            <th>File Name</th>
                            <th>Folder</th>
                            <th>File Type</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($org as $orgFile)
                        <?php
                        $dateTimeCreated = DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $orgFile['created_at']);
                        $created_at = $dateTimeCreated !== false ? $dateTimeCreated->format('Y-m-d H:i') : "";


                        $dateTimeUpdated = DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $orgFile['updated_at']);
                        $updated_at = $dateTimeUpdated !== false ? $dateTimeUpdated->format('Y-m-d H:i') : "";
                        ?>
                        <tr>
                            <th scope="row">{{$orgFile['file_name'] ??""}}</th>
                            <td>{{$orgFile['folder']??""}}</td>
                            <td>{{$orgFile['file_type']??""}}</td>
                            <td>{{$created_at}}</td>
                            <td>{{$updated_at}}</td>
                            <td>{{$orgFile['is_active']==0 ? 'Deleted':'File Present'}}</td>
                            <td>

                                @if($orgFile['is_active'])
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/dashboard/deleteFile/{{$orgFile['id']}}"><i class="bx bx-trash me-2"></i> Delete</a>
                                    </div>
                                </div>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        @endforeach
        <!--/ Responsive Table -->
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
@stop