@extends('layouts.admin')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="content-wrapper">
        <!-- Responsive Table -->
        <div class="card">
            <div class="row"> <h5 class="card-header">Organizations</h5>  <a href="/dashboard/createOrganization">Create Organizations</a></div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr class="text-nowrap">
                            <th>Org Name</th>
                            <th>Org Contact Name</th>
                            <th>Org Contact Number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orgs as $org)
                        <tr> 
                            <th scope="row">{{$org->id}}</th>
                            <td>{{$org->org_contact_name}}</td>
                            <td>{{$org->org_contact_number}}</td>
                            <td>{{$org->org_is_active==1 ? 'Active':'InActive'}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/dashboard/editOrganization/{{$org->id}}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                                        <a class="dropdown-item" href="/dashboard/deleteOrganization/{{$org->id}}"><i class="bx bx-trash me-2"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Responsive Table -->
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
@stop