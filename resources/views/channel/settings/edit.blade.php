@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Channel Settings</div>

                <div class="panel-body">
                    <form action="/channel/{{ $channel->slug }}/edit" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $channel->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Unique URL</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-addon">{{ config('app.url') }}/channel/</div>
                                    <input id="slug" type="text" class="form-control" name="slug" value="{{ old('slug') ? old('slug') : $channel->slug }}" required>
                                </div>

                                @if ($errors->has('slug'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                            
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <textarea name="description" id="description" class="form-control" required>{{ old('description') ? old('description') : $channel->description }}</textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image" class="col-md-4 control-label">Channel Image</label>

                            <div class="col-md-6">
                                <input type="file" name="image" id="image" />
                            </div>
                        </div>
                        
                        <button class="btn btn-default" type="submit">Update</button>
                        
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
