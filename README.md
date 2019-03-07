# laravel-gridview
An implementation of Yii2's Grid View with such a simple API that it *shouldn't* need explaining.

Even though this can be used in production, it is best used as a placeholder for something better.

## Why?

Why don't you want super fast prototyping of one the most used components in web applications?

You can literally copy and paste the code for this around and customise it for each record type you have.

## What does this offer that others don't?

Simplicity. This is not a widget, it is simply a helper for `@include`. It provides a set of functions for your chosen views for each part of the grid view.

In fact, it is so simple and free form in its construction that you could manually instantiate each class (including columns) and render them separately in any part of the HTML you like.

## What query goes in?

Everything but paginate.

This plugin will not perform filter or search logic for you.

So, as an example (do not actually use this), if your grid was called `user`:

```php
$users = User::query()
    ->withTrashed()
    ->where('email', 'like', input('user-email'))
    ->orderBy(
        ltrim(input('user-sort'), '-'), 
        strpos(input('user-sort'), '-') === 0 ? 'DESC' : 'ASC'
    )
);
```

This makes it very adaptable to changes and refactoring, such as using vue.js instead.

## Dos this need JavaScript?

Nope, that's all upto your own choosing.

## Is there an example to get us started?

Sure, here you go, this goes in a view:

```php
{!!
    \sammaye\Grid\Grid::make('user')
        ->setData($users_q)
        ->setDataRowAttributes(function($grid, $row){
            $options = [];
            if ($row->trashed()) {
                $options['class'] = 'table-danger';
            }
            return $options;
        })
        ->setHeaderRowAttributes(['class' => 'thead-dark'])
        ->setColumns([
            \sammaye\Grid\Column::make('id')
                ->setDataCellTag('th')
                ->setLabel(__('#'))
                ->setAttributes(['scope' => 'row']),
            \sammaye\Grid\Column::make('email')
                ->setDataContent(function($column, $row){
                    return $row->email . (
                        $row->hasVerifiedEmail()
                            ? '<span class="text-success">' . __(
                                'Verified: :date',
                                ['date' => $row->email_verified_at]
                            ) . '</span>'
                            : '<span class="text-danger">' . __('Not Verified') . '</span>'
                    );
                }),
            \sammaye\Grid\Column::make('first_name'),
            \sammaye\Grid\Column::make('last_name'),
            \sammaye\Grid\Column::make('identity_verified')
                ->setDataContent(function($column, $row){
                    if($row->hasVerifiedIdentity()){
                        return '<span class="text-success">' . __(
                            'Verified: :date',
                            ['date' => $row->identity_verified_at]
                        ) . '</span>';
                    }elseif($row->hasPendingVerifiedIdentity()) {
                        return '<span class="text-warning">' . __(
                            'Pending: :date',
                            ['date' => $row->identity_verification_requested_at]
                        ) . '</span>';
                    }elseif($row->hasRejectedVerifiedIdentity()){
                        return '<span class="text-danger">' . __(
                            'Rejected: :date',
                            ['date' => $row->identity_verification_rejected_at]
                        ) . '</span>';
                    }else{
                        return '<span class="text-muted">' . __('Awaiting') . '</span>';
                    }
                }),
            \sammaye\Grid\Column::make('has_documents')
                ->setDataContent(function($column, $row){
                    return $row->verificationDocuments()->count() > 0
                        ? '<span class="text-success">Yes</span>'
                        : '<span class="text-danger">No</span>';
                }),
            \sammaye\Grid\Column::make('created_at'),
            \sammaye\Grid\Column::make('updated_at'),
            \sammaye\Grid\Column::make('deleted_at'),
            \sammaye\Grid\ActionColumn::make('actions')
                ->setEditButton(function($column, $row){
                    return route('admin.user.edit', ['user' => $row]);
                }, __('Edit'))
                ->setDeleteButton(function($column, $row){
                    return route('admin.user.destroy', ['user' => $row]);
                }, __('Delete'))
                ->setRestoreButton(function($column, $row){
                    return route('admin.user.restore', ['user' => $row]);
                }, __('Restore'))
        ])
    ->getTable()
!!}
```

