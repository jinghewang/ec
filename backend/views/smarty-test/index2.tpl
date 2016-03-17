{$msg}

{$model.id}
{$model.name}


{foreach $items as $item}
    {$item.id} {$item.name} {$item.age}
{/foreach}