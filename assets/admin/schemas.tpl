<input type="hidden" value="schemas" id="js-page-type">

<section class="mini-layout">
    <div class="frame_title clearfix">
        <div class="pull-left">
            <span class="help-inline"></span>
            <span class="title">{lang("Схемы блоков", 'bricks')}</span>
        </div>

        <div class="pull-right">
            <div class="d-i_b">
                {if $schemas}
                    <button class="btn btn-small btn-danger disabled action_on" id="del_in_search" onclick="$('.schemas_delete_dialog').modal();" disabled="disabled"><i class="icon-trash"></i>{lang("Удалить", 'bricks')}</button>
                {/if}

                <a href="/admin/components/init_window/bricks/schemas/create" class="btn btn-small pjax btn-success"><i class="icon-plus-sign icon-white"></i>{lang("Создать схему", 'bricks')}</a>

                <a href="/admin/components/init_window/bricks/data" class="btn btn-small pjax">{lang("Блоки", 'bricks')}</a>
            </div>
        </div>
    </div>

    {if !$schemas}
        <div class="alert alert-info m-t_20">
            {lang("Список схем пуст", 'bricks')}
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
                <th></th>
            </thead>

            <tbody>
                {foreach $schemas as $schema}
                    <tr>
                        <td class="t-a_c">
                            <span class="frame_label">
                                <span class="niceCheck">
                                    <input type="checkbox" name="ids" value="{$schema['id']}">
                                </span>
                            </span>
                        </td>
                        <td>{$schema['name']}</td>
                        <td>{$schema['title']}</td>
                        <td><a href="/admin/components/cp/bricks/schemas/edit/{$schema['id']}" class="pjax">{lang("Редактировать", 'bricks')}</a></td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {/if}
</section>

{if $schemas}
<div class="modal hide fade schemas_delete_dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>{lang("Удалить схему(-ы)?", 'bricks')}</h3>
    </div>

    <div class="modal-body">
        {lang("Удаление схемы повлечёт удаление всех дочерних блоков.", 'bricks')}
    </div>

    <div class="modal-footer">
        <a class="btn" onclick="$('.modal').modal('hide');">{lang("Отмена", 'bricks')}</a>
        <a class="btn btn-primary" onclick="bricks.deleteMany();$('.modal').modal('hide');">{lang("Удалить", 'bricks')}</a>
    </div>
</div>
{/if}