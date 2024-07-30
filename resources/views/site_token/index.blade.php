@extends('layouts.admin')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="content-wrapper">
        <!-- Responsive Table -->
        <div class="card">
            <div class="row"> <h5 class="card-header">Tokens</h5>  <a href="/dashboard/createSiteToken">Create Token</a></div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr class="text-nowrap">
                            <th>Site Name</th>
                            <th>Site Contact Person</th>
                            <th>Site Token</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sites as $site)
                        <tr> 
                            <td>{{$site->org_name}}</td>
                            <td>{{$site->org_contact_name}}</td>
                            <td>{{$site->site_auth_token}}</td>
                            <td>{{$site->token_is_active==1 ? 'Active':'InActive'}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/dashboard/editSiteToken/{{$site->id}}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                                        <a class="dropdown-item" href="/dashboard/deleteSiteToken/{{$site->id}}"><i class="bx bx-trash me-2"></i> Delete</a>
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