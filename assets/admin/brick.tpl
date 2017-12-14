<input type="hidden" value="brick" id="js-page-type">

<section class="mini-layout">
    <div class="frame_title clearfix">
        <div class="pull-left">
            <span class="help-inline"></span>
            <span class="title">{lang("Блок", 'bricks')}</span>
        </div>

        <div class="pull-right">
            <div class="d-i_b">
                <a href="/admin/components/cp/bricks/data" class="t-d_n m-r_15 pjax"><span class="f-s_14">←</span><span class="t-d_u">Вернуться</span></a>

                <button type="button" class="btn btn-small btn-primary formSubmit" data-form="#brick-form" data-submit data-action="toedit"><i class="icon-white icon-ok"></i>{lang("Сохранить", 'bricks')}</button>

                <button type="button" class="btn btn-small formSubmit" data-form="#brick-form" data-submit data-action="tomain"><i class="icon-check"></i>{lang("Сохранить и выйти", 'bricks')}</button>

                {if $type === 'edit'}
                    <button type="button" class="btn btn-small btn-danger" onclick="$('.brick_delete_dialog').modal();"><i class="icon-trash"></i>{lang("Удалить", 'bricks')}</button>
                {/if}
            </div>
        </div>
    </div>
</section>


<div class="row-fluid form-horizontal">
    <form method="post" enctype="multipart/form-data" id="brick-form">

        <input type="hidden" name="schema_id" value="{$schema['id']}">

        <table class="table table-bordered table-hover table-condensed content_big_td">
            <thead>
                <tr>
                    <th colspan="6">
                        {if $type === 'create'}
                            {lang("Создание", 'bricks')}
                        {else :}
                            {lang("Редактирование", 'bricks')}
                        {/if}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6">
                        <div class="inside_padd">
                            <div class="row-fluid">

                                <div class="control-group">
                                    <label class="control-label" for="brick-name">{lang("Название", 'bricks')}:</label>
                                    <div class="controls">
                                        <input type="text" value="{$brick['name']}" name="name" id="brick-name" required maxlength="50">
                                        <div class="help-block">{lang("латиницей", 'bricks')}</div>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="brick-title">{lang("Заголовок", 'bricks')}:</label>
                                    <div class="controls">
                                        <input type="text" value="{$brick['title']}" name="title" id="brick-title" required maxlength="100">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="brick-group">{lang("Группа", 'bricks')}:</label>
                                    <div class="controls">
                                        <select id="brick-group" name="group_id">
                                            <option value="0">{lang('Выберите группу', 'bricks')}</option>

                                            {foreach $groups as $group}
                                                <option value="{$group['id']}"{if $group['id'] === $brick['group_id']} selected{/if}>{$group['title']}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>

                                { $i = 1 }
                                {foreach $schema['fields'] as $field}
                                    <div class="control-group">
                                        <label class="control-label" for="brick-field-{$i}">{$field['title']}:</label>
                                        <div class="controls">
                                            {$CI->template->include_tpl($field['type'], $fieldsPath, [
                                                'id' => 'brick-field-' . $i,
                                                'name' => 'fields[' . $field['name'] . ']',
                                                'value' => $brick['fields'][$field['name']],
                                            ])}
                                        </div>
                                    </div>
                                    { $i++ }
                                {/foreach}
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

{if $type === 'edit'}
<div class="modal hide fade brick_delete_dialog">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>{lang("Удалить блок?", 'bricks')}</h3>
    </div>
    <div class="modal-footer">
        <a class="btn" onclick="$('.modal').modal('hide');">{lang("Отмена", 'bricks')}</a>
        <a class="btn btn-primary" onclick="bricks.delete({$brick['id']});$('.modal').modal('hide');">{lang("Удалить", 'bricks')}</a>
    </div>
</div>
{/if}