<{!! $column->getDataCellTag($row) !!} {!! $column->getDataCellAttributes($row) !!}>
    {!! $column->getDataContent($row) ?? $column->getEmptyDataContent($row) !!}
</{!! $column->getDataCellTag($row) !!}>
