<table {!! $grid->getAttributes() !!}>
    <thead>
        @if($grid->getShowHeaderRow() && $grid->hasHeaderRow())
            <tr {!! $grid->getHeaderRowAttributes() !!}>
                @foreach($grid->getColumns() as $column)
                    {!! $column->getHeaderCell() ?: $column->getEmptyHeaderCell() !!}
                @endforeach
            </tr>
        @endif
        @if($grid->getShowFilterRow() && $grid->hasFilterRow())
            <tr {!! $grid->getFilterRowAttributes() !!}>
                <form method="GET" action="{!! $grid->getFilterFormActionUrl() !!}">
                    <input type="hidden"
                           name="{{ $grid->getName() . '-' . $grid->getPageName() }}"
                           id="{{ $grid->getName() . '-' . $grid->getPageName() }}"
                           value="{{ $grid->getData()->currentPage() }}"
                    />
                    @if(request($grid->getName() . '-' . $grid->getSortName()))
                        <input type="hidden"
                               name="{{ $grid->getName() . '-' . $grid->getSortName() }}"
                               id="{{ $grid->getName() . '-' . $grid->getSortName() }}"
                               value="{{ request($grid->getName() . '-' . $grid->getSortName()) }}"
                        />
                    @endif
                    @foreach($column->getWithInput() as $input)
                        @if(request($input))
                        <input type="hidden"
                               name="{{ $input }}"
                               value="{{ request($input) }}"
                        />
                        @endif
                    @endforeach
                    @foreach($grid->getColumns() as $column)
                        {!! $column->getFilterCell() ?: $column->getEmptyFilterCell() !!}
                    @endforeach
                    <button type="submit" class="d-none">{{ __('Search') }}</button>
                </form>
            </tr>
        @endif
    </thead>
    <tbody>
        @forelse($grid->getData() as $row)
            <tr {!! $grid->getDataRowAttributes($row) !!}>
                @foreach($grid->getColumns() as $column)
                    {!! $column->getDataCell($row) ?: $column->getEmptyDataCell($row) !!}
                @endforeach
            </tr>
        @empty
            <tr colspan="<?= count($grid->getColumns()) ?>">
                {!! $grid->getEmptyData() !!}
            </tr>
        @endforelse
    </tbody>
    @if($grid->getShowFooterRow() && $grid->hasFooterRow())
        <tfoot>
            <tr {!! $grid->getFooterRowAttributes() !!}>
                @foreach($grid->getColumns() as $column)
                    {!! $column->getFooterCell() ?: $column->getEmptyFooterCell() !!}
                @endforeach
            </tr>
        </tfoot>
    @endif
</table>
<div>
    {!! $grid->getData()->links() !!}
</div>
