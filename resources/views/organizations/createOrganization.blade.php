@extends('layouts.admin')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-xl-12">
            <div class="row">
                <!-- HTML5 Inputs -->
                <form action="/dashboard/storeOrganization" enctype="multipart/form-data"  method="POST">
                    @csrf
                    @if(isset($org))
                    <input type="hidden" name="id" value="{{$org->id}}">
                    @endif
                    <div class="card mb-4">
                        <h5 class="card-header">Create Organization</h5>

                        <div class="card-body">
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Name</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="org_name" value="{{$org->org_name ?? ''}}" placeholder="Enter Organization Name" id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Description</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="textarea" name="org_description" value="{{$org->org_description ?? ''}}" placeholder="Enter Organization Description" id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Organization Contact Name</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="org_contact_name" value="{{$org->org_contact_name ?? ''}}" placeholder="Enter Organization Contact Name" id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Organization Contact Email</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="org_contact_email" value="{{$org->org_contact_email ?? ''}}" placeholder="Enter Organization Contact Email" id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Organization Contact Number</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="org_contact_number" value="{{$org->org_contact_number ?? ''}}" placeholder="Enter Organization Contact Number" id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Storage Name</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="storage_name" value="{{$org->storage_name ?? ''}}" placeholder="Enter Storage Name" id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Organization Address</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="org_address" value="{{$org->org_address ?? ''}}" placeholder="Enter Organization Address" id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Organization Type</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="org_type" value="{{$org->org_type ?? ''}}" placeholder="Enter Organization Type" id="html5-text-input" />
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="exampleFormControlSelect1" class="form-label">Select Active</label>
                                <select class="form-select" id="exampleFormControlSelect1" aria-label="Select Active" name="org_is_active">
                                    <option selected>Select Organization Active</option>
                                    <option value="1" @if(isset($org) && $org->org_is_active== 1) selected @endif>YES</option>
                                    <option value="0" @if(isset($org) && $org->org_is_active== 0) selected @endif>NO</option>
                                </select>
                            </div>
                            <div class="mb-3 row">
                                <label for="exampleFormControlSelect1" class="form-label">Select Parent</label>
                                <select class="form-select" id="exampleFormControlSelect1" aria-label="Select Parent Organization" name="parent_id">
                                    <option selected>Select Parent Organization</option>
                                    <option value="0" @if(isset($org) && $org->parent_id == 0) selected @endif>Parent</option>
                                    @foreach($orgs as $Org)
                                        <option value="{{$Org['id']}}" @if(isset($org) && $org->parent_id == $Org->id) selected @endif>{{$Org['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Logo</label>
                                <input type="file" name="logo" id="inputImage" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Banner</label>
                                <input type="file" name="org_banner" id="inputImage" class="form-control">
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-search-input" class="col-md-2 col-form-label"></label>
                                <div class="col-md-10">
                                    <input class="btn btn-primary" type="submit" value="submit" id="html5-search-input" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop