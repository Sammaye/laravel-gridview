<td>
    @if($column->getShowEditButton($row))
        <a href="{{ $column->getEditButtonUrl($row) }}">{{ $column->getEditButtonCaption($row) }}</a>
    @endif
    @if($column->getShowViewButton($row))
        <a href="{{ $column->getViewButtonUrl($row) }}">{{ $column->getViewButtonCaption($row) }}</a>
    @endif
    @if($column->getShowDeleteButton($row))
        <a
            href="{{ $column->getDeleteButtonUrl($row) }}"
            onclick="
                event.preventDefault();
                document.getElementById('{{ $column->getElementPrefix() }}-delete-{{ $row->id }}-form').submit();
            "
        >
            {{ $column->getDeleteButtonCaption($row) }}
        </a>
        <form
            action="{{ $column->getDeleteButtonUrl($row) }}"
            method="POST"
            id="{{ $column->getElementPrefix() }}-delete-{{ $row->id }}-form"
        >
            @csrf
            @method('DELETE')
        </form>
    @endif
    @if($column->getShowRestoreButton($row))
        <a
            href="{{ $column->getRestoreButtonUrl($row) }}"
            onclick="
                event.preventDefault();
                document.getElementById('{{ $column->getElementPrefix() }}-restore-{{ $row->id }}-form').submit();
            "
        >
            {{ $column->getRestoreButtonCaption($row) }}
        </a>
        <form
            action="{{ $column->getRestoreButtonUrl($row) }}"
            method="POST"
            id="{{ $column->getElementPrefix() }}-restore-{{ $row->id }}-form"
        >
            @csrf
            @method('PUT')
        </form>
    @endif
</td>
