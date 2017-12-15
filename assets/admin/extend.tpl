<input type="hidden" value="relations" id="js-page-type">

<table class="table table-bordered table-hover table-condensed t-l_a js-bricks-relations-table">
    <thead>
        <th>{lang("Название", 'bricks')}</th>
        <th>{lang("Заголовок", 'bricks')}</th>

        {if $groups}
            <th>{lang("Группа", 'bricks')}</th>
        {/if}

        <th class="span2 bricks-th-select">
            <select class="js-bricks-select">
                {foreach $bricks as $brick}
                    <option data-id="{$brick['id']}" data-name="{$brick['name']}" data-group="{$brick['group_id']}">{$brick['title']}</option>
                {/foreach}
            </select>

            <a class="btn btn-small btn-success bricks-add-btn js-bricks-brick-add"><i class="icon-plus-sign icon-white" style="margin-right: 0;"></i></a>
        </th>
    </thead>

    <tbody class="sortable">
        <tr class="bricks-fields-table-proto js-bricks-row-proto">
            <td>%name%</td>
            <td>%title%</td>

            {if $groups}
                <td>
                    <input type="hidden" name="bricks[id][]" value="%id%">
                    <select class="js-bricks-group-select" name="bricks[group_id][]">
                        <option value="0">{lang('Выберите группу', 'bricks')}</option>

                        {foreach $groups as $group}
                            <option value="{$group['id']}">{$group['title']}</option>
                        {/foreach}
                    </select>
                </td>
            {/if}

            <td class="t-a_c">
                <div class="btn btn-small btn-danger js-bricks-remove-btn"><i class="icon-trash"></i>{lang("Удалить", 'bricks')}</div>
            </td>
        </tr>

        {foreach $relations as $relation}
            { $brick = $bricks[$relation['brick_id']] }
            <tr>
                <td>{$brick['name']}</td>
                <td>{$brick['title']}</td>

                {if $groups}
                    <td>
                        <select class="js-bricks-group-select" name="bricks[group_id][]">
                            <option value="0">{lang('Выберите группу', 'bricks')}</option>

                            {foreach $groups as $group}
                                <option value="{$group['id']}"{if $group['id'] === $relation['group_id']} selected{/if}>{$group['title']}</option>
                            {/foreach}
                        </select>
                    </td>
                {/if}

                <td class="t-a_c">
                    <input type="hidden" name="bricks[id][]" value="{$brick['id']}">
                    <div class="btn btn-small btn-danger js-bricks-remove-btn"><i class="icon-trash"></i>{lang("Удалить", 'bricks')}</div>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>
