@extends('voyager::master')

@section('page_title', 'Multi Language Translation Label')

@section('css')
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

@endsection

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-world"></i>
        Multi Language Translation Label
    </h1>
@stop

@section('content')
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-bordered panel-body">

    <form method="POST" action="{{ route('voyager.translations.create') }}">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-4">
                <label>Key:</label>
                <input type="text" name="key" class="form-control" placeholder="Enter Key...">
            </div>
            <div class="col-md-4">
                <label>Value:</label>
                <input type="text" name="value" class="form-control" placeholder="Enter Value...">
            </div>
            <div class="col-md-4" style="margin-top: 21px;">
                <button type="submit" class="btn btn-success">Add</button>
            </div>
        </div>
    </form>


    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th>Key</th>
            @if($languages->count() > 0)
                @foreach($languages as $language)
                    <th>{{ $language->name }} ({{ $language->code }})</th>
                @endforeach
            @endif
            <th width="80px;">Action</th>
        </tr>
        </thead>
        <tbody>
            @if($columnsCount > 0)
                @foreach($columns[0] as $columnKey => $columnValue)
                    <tr>
                        <td><a href="#" class="translate-key" data-title="Enter Key" data-type="text" data-pk="{{ $columnKey }}" data-url="{{ route('voyager.translation.update.json.key') }}">{{ $columnKey }}</a></td>
                        @for($i=1; $i<=$columnsCount; ++$i)
                        <td><a href="#" data-title="Enter Translate" class="translate" data-code="{{ $columns[$i]['lang'] }}" data-type="textarea" data-pk="{{ $columnKey }}" data-url="{{ route('voyager.translation.update.json') }}">{{ isset($columns[$i]['data'][$columnKey]) ? $columns[$i]['data'][$columnKey] : '' }}</a></td>
                        @endfor
                        <td><button data-action="{{ route('voyager.translations.destroy', $columnKey) }}" class="btn btn-danger btn-xs remove-key">Delete</button></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>  
</div>
</div>
@endsection

@section('javascript')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

    <script type="text/javascript">
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('.translate').editable({
            params: function(params) {
                params.code = $(this).editable().data('code');
                return params;
            }
        });


        $('.translate-key').editable({
            validate: function(value) {
                if($.trim(value) == '') {
                    return 'Key is required';
                }
            }
        });


        $('body').on('click', '.remove-key', function(){
            var cObj = $(this);


            if (confirm("Are you sure want to remove this item?")) {
                $.ajax({
                    url: cObj.data('action'),
                    method: 'DELETE',
                    success: function(data) {
                        cObj.parents("tr").remove();
                        alert("Your imaginary file has been deleted.");
                    }
                });
            }


        });
    </script>

@endsection


