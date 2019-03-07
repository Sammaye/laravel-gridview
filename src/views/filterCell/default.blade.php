<td {!! $column->getFilterCellAttributes() !!}>
    <input type="text" class="form-control" name="{!! $column->getInputName() !!}" value="{{ $column->getInputValue() }}"/>
</td>
