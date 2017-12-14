<input type="hidden" value="bricks" id="js-page-type">

<section class="mini-layout">
    <div class="frame_title clearfix">
        <div class="pull-left">
            <span class="help-inline"></span>
            <span class="title">{lang("Блоки", 'bricks')}</span>
        </div>

        <div class="pull-right">
            <div class="d-i_b">
                {if $bricks}
                    <button class="btn btn-small btn-danger disabled action_on" id="del_in_search" onclick="$('.bricks_delete_dialog').modal();" disabled="disabled"><i class="icon-trash"></i>{lang("Удалить", 'bricks')}</button>
                {/if}

                <a href="/admin/components/init_window/bricks/groups" class="btn btn-small pjax">{lang("Группы", 'bricks')}</a>

                <a href="/admin/components/init_window/bricks/schemas" class="btn btn-small pjax">{lang("Схемы блоков", 'bricks')}</a>
            </div>
        </div>
    </div>

    {if !$schemas}
        <div class="alert alert-info m-t_20">
            {lang("Создайте схему, чтобы иметь возможность создавать блоки.", 'bricks')}
        </div>
    {else :}
        <table class="table table-bordered table-hover table-condensed t-l_a">
            <thead>
                <th class="t-a_c span1">
                    <span class="frame_label">
                        <span class="niceCheck">
                            <input type="checkbox">
                        </span>
                    </span>
                </th>
                <th>{lang("Название", 'bricks')}</th>
                <th>{lang("Заголовок", 'bricks')}</th>
                <th>{lang("Схема", 'bricks')}</th>

                {if $groups}
                    <th>{lang("Группа", 'bricks')}</th>
                {/if}

                <th class="span2 bricks-th-select">
                    <select class="js-bricks-schemas-select">
                        {foreach $schemas as $schema}
                            <option value="{$schema['id']}">{$schema['title']}</option>
                        {/foreach}
                    </select>

                    <a class="btn btn-small btn-success bricks-add-btn js-bricks-brick-add"><i class="icon-plus-sign icon-white" style="margin-right: 0;"></i></a>
                </th>
            </thead>

            <tbody{if count($bricks) > 1} class="sortable save_positions" data-url="/admin/components/init_window/bricks/data/savepositions"{/if}>
                {foreach $bricks as $brick}
                    <tr>
                        <td class="t-a_c">
                            <span class="frame_label">
                                <span class="niceCheck">
                                    <input type="checkbox" name="ids" value="{$brick['id']}">
                                </span>
                            </span>
                        </td>
                        <td>{$brick['name']}</td>
                        <td>{$brick['title']}</td>
                        <td>{$schemas[$brick['schema_id']]['title']}</td>

                        {if $groups}
                            <td>
                                <select class="js-bricks-group-select" data-brick="{$brick['id']}">
                                    <option value="0">{lang('Выберите группу', 'bricks')}</option>

                                    {foreach $groups as $group}
                                        <option value="{$group['id']}"{if $group['id'] === $brick['group_id']} selected{/if}>{$group['title']}</option>
                                    {/foreach}
                                </select>
                            </td>
                        {/if}

                        <td><a href="/admin/components/cp/bricks/data/edit/{$brick['id']}" class="pjax">{lang("Редактировать", 'bricks')}</a></td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {/if}
</section>

{if $bricks}
<div class="modal hide fade bricks_delete_dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>{lang("Удалить блок(-и)?", 'bricks')}</h3>
    </div>

    <div class="modal-footer">
        <a class="btn" onclick="$('.modal').modal('hide');">{lang("Отмена", 'bricks')}</a>
        <a class="btn btn-primary" onclick="bricks.deleteMany();$('.modal').modal('hide');">{lang("Удалить", 'bricks')}</a>
    </div>
</div>
{/if}