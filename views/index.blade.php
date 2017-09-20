<div class="tableView-wrapper">

    @if(count($tableView->searchableFields()))

        <form name="searchForm" class="pull-right" method="GET">

            <div class="pull-right col-sm-3">
                <div class="input-group">
                    <input type="text"
                           placeholder="Search"
                           name="q"
                           class="input-sm form-control"
                           value="{{ Request::get('q') }}">
                    <span class="input-group-btn">
                            <button type="button" class="btn btn-sm btn-primary"> Search </button>
                    </span>
                </div>
            </div>

        </form>

    @endif

    <table class="{{$tableView->getTableClass()}}" id="{{$tableView->id()}}">
        <thead>
        <tr>
            @foreach($tableView->columns() as $column)

                <td>

                    {{ $column->title() }}

                </td>

            @endforeach
        </tr>
        </thead>
        <tbody>

        @foreach($tableView->data() as $dataModel)
            <tr>

                @foreach($tableView->columns() as $column)
                    <td>
                        {!!  $column->rowValue($dataModel)  !!}
                    </td>
                @endforeach

            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="tableView-pagination">
    @if($tableView->hasPagination())
        {{ $tableView->data()->links() }}
    @endif
</div>

@if($tableView->dataTable)

    @push(\Illuminate\Support\Facades\Config::get('tableView.dataTable.css.stack_name'))

        @foreach(\Illuminate\Support\Facades\Config::get('tableView.dataTable.css.paths') as $path)
            <link href="{{$path}}" rel="stylesheet">
        @endforeach

    @endpush

    @push(\Illuminate\Support\Facades\Config::get('tableView.dataTable.js.stack_name'))
        @foreach(\Illuminate\Support\Facades\Config::get('tableView.dataTable.js.paths') as $path)
            <script src="{{$path}}"></script>
        @endforeach

        <script>
            $(function () {
                $('#{{$tableView->id()}}').DataTable({
                    "bSort": true,
                    "aaSorting": []
                });
            });
        </script>

    @endpush

@endif