@extends('layouts.admin')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-xl-12">
            <div class="row">
                <!-- HTML5 Inputs -->
                <form action="/dashboard/storeSiteToken" enctype="multipart/form-data"  method="POST">
                    @csrf
                    @if(isset($site))
                    <input type="hidden" name="id" value="{{$site->id}}">
                    @endif
                    <div class="card mb-4">
                        <h5 class="card-header">Create Site Token</h5>

                        <div class="card-body">
                            
                        <div class="mb-3 row">
                                <label for="exampleFormControlSelect1" class="form-label">Select Organization</label>
                                <select class="form-select" id="exampleFormControlSelect1" aria-label="Select Organization" name="org_id">
                                    <option selected>Select Organization</option>
                                    @foreach($orgs as $Org)
                                        <option value="{{$Org['id']}}" @if(isset($site->org_id) && $site->org_id == $Org->id) selected @endif>{{$Org['org_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            

                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Site Passphrase</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="site_auth_token" placeholder="Enter a passphrase" id="html5-text-input" />
                                    <label for="html5-text-input" class="col-md-6 col-form-label">{{$site->site_auth_token ??""}}</label>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="exampleFormControlSelect1" class="form-label">Select Active</label>
                                <select class="form-select" id="exampleFormControlSelect1" aria-label="Select Active" name="token_is_active">
                                    <option selected>Select  Active</option>
                                    <option value="1" @if(isset($site) && $site->token_is_active== 1) selected @endif>YES</option>
                                    <option value="0" @if(isset($site) && $site->token_is_active== 0) selected @endif>NO</option>
                                </select>
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