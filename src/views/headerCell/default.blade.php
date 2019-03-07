<th {!! $column->getHeaderCellAttributes() !!}>
    @if($column->getSortable())
        <a href="{{ $column->getSortUrl() }}" class="sort{{
            $column->getSortDirection() === 1
                ? ' asc'
                : ($column->getSortDirection() === -1 ? ' desc' : '')
        }}">
            {!! e($column->getLabel()) !!}
        </a>
    @else
        {!! e($column->getLabel()) !!}
    @endif
</th>
